<?php

    require "../arquivosFactoryMethod/fabricaUser/userConcretCreate.php";
    require_once "../crudTemplateMethod/crudUsuario.php";

    // Verificando se o método da requisição acionada pelo formulário de cadastro do usuário é POST.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        header('Content-Type: application/json');

        try {

            // Pegando os valores dos inputs.
            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $celular = $_POST['celular'];
            $sexo = $_POST['sexo'];
            $senha = $_POST['senha'];
            $dataNascimento = $_POST['data-nascimento'];
            $cep = $_POST['cep'];
            $endereco = $_POST['endereco'];
            $numeroEndereco = $_POST['numero'];
            $complemento = $_POST['complemento'];
            $referencia = $_POST['referencia'];
            $bairro = $_POST['bairro'];
            $cidade = $_POST['cidade'];
            $estado = $_POST['estado'];
            $email = 'inexistente'; // Alterar conforme necessário

            $fabricaUsuario = new UserConcreteCreator();
            

            $usuario = $fabricaUsuario->criarUsuario($nome, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado, 'Cliente');

            echo 'Número Endereço: ' . $usuario->getNumeroEndereco() . '<br>';

            $crudUsuario = new CrudUsuario();
            $resultadoCadastroUsuario = $crudUsuario->createEntidade($usuario);

            

            if ($resultadoCadastroUsuario) {


                echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso.']);

            } else {

                echo json_encode(['status' => 'error', 'message' => 'Erro no cadastro.']);

            }

        } catch (Exception $excecao) {

            echo json_encode(['status' => 'error', 'message' => 'Ocorreu um erro: ' . $excecao->getMessage()]);

        }

    }

?>
