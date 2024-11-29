<?php

    header('Content-Type: application/json');

    require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";

    try {

        // Verifique se o ID foi especificado na query string
        if (!isset($_GET['id'])) {
            throw new Exception("ID do produto não especificado.");
        }

        $id = (int)$_GET['id']; // Converta o ID para inteiro

        // Crie uma instância da sua classe que contém os métodos
        $crudProduto = new CrudProduto();

        // Chame o método lerEntidade com o ID e o tipo 'Produtos'
        $entidade = $crudProduto->lerEntidade($id, 'Produtos');

        if ($entidade === null) {

            echo json_encode(["status" => "erro", "mensagem" => "Entidade não encontrada."]);

        } else {

            // Se for um kit, processar os produtos do kit
            if ($entidade['tipoProduto'] === 'Kit' && !empty($entidade['produtosKit'])) {
                $entidade['produtosKit'] = array_map(function($produto) {
                    return [
                        'id' => $produto['id'] ?? null,
                        'imagemProduto' => $produto['imagemProduto'] ?? null,
                        'nomeProduto' => $produto['nomeProduto'] ?? null,
                        'valorProduto' => $produto['valorProduto'] ?? null,
                        'quantidade' => $produto['quantidade'] ?? null,
                        'categoria' => $produto['categoria'] ?? null,
                        'tipoProduto' => $produto['tipoProduto'] ?? null,
                        'descricaoProduto' => $produto['descricaoProduto'] ?? null
                    ];
                }, $entidade['produtosKit']);
            }

            echo json_encode(["status" => "sucesso", "entidade" => $entidade]);

        }
        
    } catch (Exception $excecao) {
        echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
    }
