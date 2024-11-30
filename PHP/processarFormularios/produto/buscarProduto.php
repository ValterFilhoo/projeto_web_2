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

            if ($produto['tipoProduto'] === 'Kit' && isset($produto['produtosKit']) && is_string($produto['produtosKit'])) {
                $produto['produtosKit'] = json_decode($produto['produtosKit'], true);
            }

            echo json_encode([
                "status" => "sucesso",
                "produto" => $produto
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
