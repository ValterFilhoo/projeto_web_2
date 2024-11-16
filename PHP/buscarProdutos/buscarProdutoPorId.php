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

    // Chame o método lerEntidade
    $entidade = $crudProduto->lerEntidade($id);

    if ($entidade === null) {

        echo json_encode(["status" => "erro", "mensagem" => "Entidade não encontrada."]);

    } else {

        echo json_encode(["status" => "sucesso", "entidade" => $entidade]);

    }

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
