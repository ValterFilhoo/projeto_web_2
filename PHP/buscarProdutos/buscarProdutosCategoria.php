<?php

require_once __DIR__ . "/../facade/ProdutoFacade.php";

header('Content-Type: application/json');

session_start(); // Inicia a sessão

try {
    
    // Verifique se a categoria foi especificada na query string
    if (!isset($_GET['categoria'])) {
        throw new Exception("Categoria não especificada.");
    }

    $categoria = $_GET['categoria'];

    // Instanciar o ProdutoFacade
    $produtoFacade = new ProdutoFacade();

    // Buscar produtos pela categoria usando o facade
    $resultado = $produtoFacade->buscarProdutosPorCategoria($categoria);

    // Exibir o resultado
    echo json_encode($resultado);

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
