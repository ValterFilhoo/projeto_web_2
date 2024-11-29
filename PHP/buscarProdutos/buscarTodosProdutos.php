<?php

    header('Content-Type: application/json');

    require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/product.php";

    session_start(); // Inicia a sessão

    try {
        
        // Verifique o tipo solicitado pela query string
        if (!isset($_GET['tipo'])) {
            throw new Exception("Tipo de entidade não especificado.");
        }

        $tipo = $_GET['tipo'];

        // Crie uma instância da sua classe que contém os métodos
        $crudProduto = new CrudProduto();

        // Chame o método listarEntidades
        $entidades = $crudProduto->listarEntidades($tipo);

        if ($entidades === null) {

            echo json_encode(["status" => "erro", "mensagem" => "Nenhuma entidade encontrada."]);

        } else {

            // Verifica se a conta autenticada é "Admin"
            $tipoConta = isset($_SESSION['tipoConta']) ? $_SESSION['tipoConta'] : 'Guest';

            // Processa cada entidade para incluir produtos do kit, se necessário
            foreach ($entidades as &$entidade) {

                // Verifica se a entidade é uma instância de Produto e se é um Kit
                if ($entidade instanceof Product && $entidade->getTipo() === 'Kit') {

                    // Verifica se a entidade tem os métodos 'obterProdutos' e 'definirProdutos'
                    if (method_exists($entidade, 'obterProdutos') && method_exists($entidade, 'definirProdutos')) {

                        $produtosKit = $entidade->obterProdutos();

                        if (is_string($produtosKit)) {
                            $produtosKit = json_decode($produtosKit, true);
                        }

                        if (is_array($produtosKit)) {
                            $produtosKitArray = array_map(function($produtoKit) {
                                return [
                                    'id' => isset($produtoKit['id']) ? (int)$produtoKit['id'] : 0,
                                    'imagemProduto' => $produtoKit['imagemProduto'] ?? '',
                                    'nomeProduto' => $produtoKit['nomeProduto'] ?? '',
                                    'valorProduto' => isset($produtoKit['valorProduto']) ? (float)$produtoKit['valorProduto'] : 0.0,
                                    'quantidade' => isset($produtoKit['quantidade']) ? (int)$produtoKit['quantidade'] : 0,
                                    'categoria' => $produtoKit['categoria'] ?? '',
                                    'tipoProduto' => $produtoKit['tipoProduto'] ?? '',
                                    'descricaoProduto' => $produtoKit['descricaoProduto'] ?? ''
                                ];
                            }, $produtosKit);

                            $entidade->definirProdutos($produtosKitArray);

                        } else {
                            $entidade->definirProdutos([]);
                        }

                    }

                }

            }

            // Converte as entidades em arrays para JSON
            $entidadesArray = array_map(function($entidade) {

                // Garantir que a entidade é um objeto antes de chamar métodos
                if (is_object($entidade)) {
                    
                    return [
                        'id' => $entidade->getId(),
                        'imagemProduto' => $entidade->getImagem(),
                        'nomeProduto' => $entidade->getNome(),
                        'valorProduto' => $entidade->getValor(),
                        'quantidade' => $entidade->getQuantidade(),
                        'categoria' => $entidade->getCategoria(),
                        'tipoProduto' => $entidade->getTipo(),
                        'descricaoProduto' => $entidade->getDescricao(),
                        'produtosKit' => method_exists($entidade, 'obterProdutos') ? $entidade->obterProdutos() : []
                    ];

                } else {
                    return $entidade;
                }

            }, $entidades);

            echo json_encode(["status" => "sucesso", "entidades" => $entidadesArray, "tipoConta" => $tipoConta]);

        }

    } catch (Exception $excecao) {
        echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
    }
