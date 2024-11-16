<?php
    // Exibir todos os erros para depuração
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Inicializa o buffer de saída para capturar qualquer saída indesejada
    ob_start();

    header('Content-Type: application/json');

    require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";

    try {
        // Verifique se a categoria foi especificada na query string
        if (!isset($_GET['categoria'])) {
            throw new Exception("Categoria não especificada.");
        }

        $categoria = $_GET['categoria'];

        // Crie uma instância da sua classe que contém os métodos
        $crudProduto = new CrudProduto();

        // Chame o método buscarProdutosPorCategoria
        $produtos = $crudProduto->buscarProdutosPorCategoria($categoria);

        if ($produtos === null) {

            $resposta = ["status" => "erro", "mensagem" => "Nenhum produto encontrado para a categoria especificada."];

        } else {
            // Transformar as instâncias dos produtos em arrays antes de retornar
            $produtosArray = array_map(function($produto) {
                return [
                    'id' => $produto->getId(),
                    'imagemProduto' => $produto->getImagem(),
                    'nomeProduto' => $produto->getNome(),
                    'valorProduto' => $produto->getValor(),
                    'quantidade' => $produto->getQuantidade(),
                    'categoria' => $produto->getCategoria(),
                    'tipoProduto' => $produto->getTipo(),
                    'descricaoProduto' => $produto->getDescricao()
                ];
            }, $produtos);

            $resposta = ["status" => "sucesso", "produtos" => $produtosArray];
        }
    } catch (Exception $excecao) {
        $resposta = ["status" => "erro", "mensagem" => $excecao->getMessage()];
    }

    // Captura qualquer saída indesejada e limpa o buffer de saída
    $output = ob_get_clean();


    // Envia a resposta JSON
    echo json_encode($resposta);
    die();
