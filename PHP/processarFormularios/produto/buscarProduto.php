<?php

// Caminhos dos arquivos.
require_once __DIR__ . "/../../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
require_once __DIR__ . "/../../crudTemplateMethod/crudProduto.php";

header('Content-Type: application/json');

session_start();

try {
    
    // Verifica se o ID do produto está definido na URL.
    if (isset($_GET['id'])) {

        $idProduto = $_GET['id'];

        $crudProduto = new CrudProduto();
        $produto = $crudProduto->lerEntidade($idProduto, "Produtos");

        if ($produto) {
            $produtoArray = [
                'id' => $produto->getId(),
                'imagemProduto' => $produto->getImagem(),
                'nomeProduto' => $produto->getNome(),
                'valorProduto' => $produto->getValor(),
                'quantidade' => $produto->getQuantidade(),
                'categoria' => $produto->getCategoria(),
                'tipoProduto' => $produto->getTipo(),
                'descricaoProduto' => $produto->getDescricao()
            ];

            // Verifica se o produto é do tipo 'Kit' antes de acessar a propriedade 'produtosKit'
            if ($produto->getTipo() === 'Kit') {
                if (isset($produto->produtos) && is_string($produto->obterProdutos())) {
                    $produto->definirProdutos(json_decode($produto->obterProdutos(), true));
                }
                $produtosKit = $produto->obterProdutos();
                $produtoArray['produtosKit'] = array_map(function($produtoKit) {
                    return [
                        'idProduto' => $produtoKit->getId(),
                        'imagemProduto' => $produtoKit->getImagem(),
                        'nomeProduto' => $produtoKit->getNome(),
                        'valorProduto' => $produtoKit->getValor(),
                        'quantidade' => $produtoKit->getQuantidade(),
                        'categoria' => $produtoKit->getCategoria(),
                        'tipoProduto' => $produtoKit->getTipo(),
                        'descricaoProduto' => $produtoKit->getDescricao()
                    ];
                }, $produtosKit);
            }

            echo json_encode([
                "status" => "sucesso",
                "produto" => $produtoArray
            ]);

        } else {
            echo json_encode([
                "status" => "erro",
                "mensagem" => "Produto não encontrado."
            ]);
        }

    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "ID do produto não fornecido."
        ]);
    }

} catch (Exception $excecao) {
    // Capturar e exibir mensagens de erro
    echo json_encode([
        "status" => "erro",
        "mensagem" => $excecao->getMessage()
    ]);
}
