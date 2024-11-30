<?php

require_once __DIR__ . "/../facade/produtoFacade.php";

header('Content-Type: application/json');

try {

    // Verifique se o ID foi especificado na query string
    if (!isset($_GET['id'])) {
        throw new Exception("ID do produto não especificado.");
    }

    $id = (int)$_GET['id']; // Converta o ID para inteiro

    // Instanciar o ProdutoFacade
    $produtoFacade = new ProdutoFacade();

    // Ler o produto pelo ID usando o facade
    $resultado = $produtoFacade->lerProdutoPorId($id);

    // Exibir o resultado
    echo json_encode($resultado);

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
