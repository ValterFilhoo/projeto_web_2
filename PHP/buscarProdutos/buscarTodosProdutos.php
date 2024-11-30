<?php

require_once __DIR__ . "/../facade/ProdutoFacade.php";

header('Content-Type: application/json');

session_start(); // Inicia a sessão

try {
    
    // Verifique o tipo solicitado pela query string
    if (!isset($_GET['tipo'])) {
        throw new Exception("Tipo de entidade não especificado.");
    }

    $tipo = $_GET['tipo'];

    // Instanciar o ProdutoFacade
    $produtoFacade = new ProdutoFacade();

    // Listar todos os produtos pelo tipo usando o facade
    $resultado = $produtoFacade->listarTodosProdutos($tipo);

    // Exibir o resultado
    echo json_encode($resultado);

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
