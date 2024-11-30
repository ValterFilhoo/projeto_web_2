<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../crudTemplateMethod/crudUsuario.php";

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID do usuário não especificado.");
    }

    $id = (int)$_GET['id']; 

    $crudUsuario = new CrudUsuario();

    $entidade = $crudUsuario->lerEntidade($id, 'Usuários');

    if ($entidade === null) {
        echo json_encode(["status" => "erro", "mensagem" => "Entidade não encontrada."]);
    } else {
        $usuarioArray = [
            'id' => $entidade->getId(),
            'nome' => $entidade->getNomeCompleto(),
            'email' => $entidade->getEmail(),
            'dataNascimento' => $entidade->getDataNascimento(),
            'cep' => $entidade->getCep(),
            'endereco' => $entidade->getEndereco(),
            'numeroEndereco' => $entidade->getNumeroEndereco(),
            'complemento' => $entidade->getComplemento(),
            'referencia' => $entidade->getReferencia(),
            'bairro' => $entidade->getBairro(),
            'cidade' => $entidade->getCidade(),
            'estado' => $entidade->getEstado(),
            'tipoConta' => $entidade->getTipoConta(),
            'telefone' => $entidade->getCelular(),
            'sexo' => $entidade->getSexo(),
            'cpf' => $entidade->getCpf()
        ];

        echo json_encode(["status" => "sucesso", "entidade" => $usuarioArray]);
    }

} catch (Exception $excecao) {
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
