<?php
require "../arquivosFactoryMethod/fabricaUser/userConcretCreate.php";
require_once "../crudTemplateMethod/crudUsuario.php";

// Verificando se o método da requisição acionada pelo formulário de cadastro do usuário é POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Definindo que o cabeçalho da requisição será um arquivo json.
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
        $email = $_POST['email'];

        // Instanciando um objeto da fábrica criadora de usuários.
        $fabricaUsuario = new UserConcreteCreator();

        // Instanciando um objeto usuário que será cadastrado no banco.
        $usuario = $fabricaUsuario->criarUsuario($nome, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado, 'Cliente');

        $crudUsuario = new CrudUsuario();

        $resultadoCadastroUsuario = $crudUsuario->criarEntidade($usuario);

        // Verificando se o resultado é verdadeiro.
        if ($resultadoCadastroUsuario) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Cadastro do usuário realizado com sucesso.']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no cadastro do usuário.']);
        }
    } catch (Exception $excecao) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Ocorreu um erro: ' . $excecao->getMessage()]);
    }

}
?>
