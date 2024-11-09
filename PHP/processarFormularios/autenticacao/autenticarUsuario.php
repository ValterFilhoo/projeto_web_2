<?php

    require_once "../arquivosFactoryMethod/fabricaUser/userConcretCreate.php";
    require_once "../crudTemplateMethod/crudUsuario.php";

    session_start();

    // Verificando se o método da requisição acionada pelo formulário de cadastro do usuário é POST.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        header('Content-Type: application/json');

        try {

            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $crudUsuario = new CrudUsuario();

            $resultadoAutenticacao = $crudUsuario->autenticarUsuario($email, $senha);

            $fabricaUsuario = new UserConcreteCreator();

            // Se o email e a senha informa confere com o usuário no banco de dados (retornado true).
            if ($resultadoAutenticacao != null) {

                // Istanciando um objeto do tipo usuário, sem passar seu id.
                $usuarioAutenticado = $fabricaUsuario->criarUsuario($resultadoAutenticacao['nomeCompleto'], $resultadoAutenticacao['email'], $resultadoAutenticacao['cpf'], $resultadoAutenticacao['celular'], $resultadoAutenticacao['sexo'], $resultadoAutenticacao['senha'], $resultadoAutenticacao['dataNascimento'], $resultadoAutenticacao['cep'], $resultadoAutenticacao['endereco'], $resultadoAutenticacao['numeroEndereco'], $resultadoAutenticacao['complemento'], $resultadoAutenticacao['referencia'], $resultadoAutenticacao['bairro'], $resultadoAutenticacao['cidade'], $resultadoAutenticacao['estado'], 'cliente');

                // Atribuindo id do usuário.
                $usuarioAutenticado->setId($resultadoAutenticacao['id']);

                $_SESSION['id'] = $usuarioAutenticado->getId();
                $_SESSION['nome'] = $usuarioAutenticado->getNome();
                $_SESSION['tipoConta'] = $usuarioAutenticado->getTipoConta();
                $_SESSION['autenticado'] = true;

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Usuario autenticado com sucesso.']);

            } else {

                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro na autenticacao.']);

            }

        } catch (Exception $excecao) {

            echo json_encode(['status' => 'erro', 'mensagem' => 'Ocorreu um erro na autenticacao: ' . $excecao->getMessage()]);

        }

    }

?>
