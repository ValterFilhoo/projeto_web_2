<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../../arquivosFactoryMethod/fabricaUser/userConcretCreate.php';
    require_once __DIR__ . '/../../crudTemplateMethod/crudUsuario.php';

    session_start();

    header('Content-Type: application/json');


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
        
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $crudUsuario = new CrudUsuario();
            $resultadoAutenticacao = $crudUsuario->autenticarUsuario($email, $senha);

            if ($resultadoAutenticacao != null) {
                
                $usuarioAutenticado = $resultadoAutenticacao;

                $_SESSION['id'] = $usuarioAutenticado->getId();
                $_SESSION['nome'] = $usuarioAutenticado->getNomeCompleto();
                $_SESSION['tipoConta'] = $usuarioAutenticado->getTipoConta();
                $_SESSION['autenticado'] = true;

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Usuário autenticado com sucesso.']);

            } else {

                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro na autenticação.']);

            }
        } catch (Exception $excecao) {

            echo json_encode(['status' => 'erro', 'mensagem' => 'Ocorreu um erro na autenticação: ' . $excecao->getMessage()]);

        }

        exit;

    } else {

        echo json_encode(['status' => 'erro', 'mensagem' => 'Método de requisição inválido.']);
        exit;

    }
