<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";

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

        echo json_encode(["status" => "sucesso", "entidades" => $entidades, "tipoConta" => $tipoConta]);

    }

} catch (Exception $e) {

    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);

}
