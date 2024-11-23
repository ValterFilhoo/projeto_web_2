<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../crudTemplateMethod/crudUsuario.php";

try {

    // Verifique se o ID foi especificado na query string
    if (!isset($_GET['id'])) {
        throw new Exception("ID do usuário não especificado.");
    }

    $id = (int)$_GET['id']; // Converta o ID para inteiro

    // Crie uma instância da sua classe que contém os métodos
    $crudUsuario = new CrudUsuario();

    // Chame o método lerEntidade com o ID e o tipo 'Usuários'
    $entidade = $crudUsuario->lerEntidade($id, 'Usuários');

    if ($entidade === null) {
        echo json_encode(["status" => "erro", "mensagem" => "Entidade não encontrada."]);
    } else {
        echo json_encode(["status" => "sucesso", "entidade" => $entidade]);
    }

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
