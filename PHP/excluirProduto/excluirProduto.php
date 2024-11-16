<?php

    // Exibir todos os erros para depuração
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Inicializa o buffer de saída para capturar qualquer saída indesejada
    ob_start();

    header('Content-Type: application/json');

    // Incluir os arquivos necessários
    require_once __DIR__ . '/../crudTemplateMethod/crudProduto.php';

    session_start(); // Inicia a sessão

    try {

        // Verifique se o ID foi especificado na requisição
        if (!isset($_POST['id'])) {
            throw new Exception("ID não especificado.");
        }

        $id = $_POST['id'];

        // Crie uma instância da sua classe que contém os métodos
        $crudProduto = new CrudProduto();

        // Chame o método deletarEntidade
        $resultado = $crudProduto->deletarEntidade($id);

        if ($resultado) {

            $resposta = ["status" => "sucesso", "mensagem" => "Produto excluido com sucesso."];

        } else {

            $resposta = ["status" => "erro", "mensagem" => "Erro ao excluir o produto."];

        }

    } catch (Exception $excecao) {

        $resposta = ["status" => "erro", "mensagem" => $excecao->getMessage()];

    }

    // Captura qualquer saída indesejada e limpa o buffer de saída
    $output = ob_get_clean();

    // Envia a resposta JSON
    echo json_encode($resposta);
    die();