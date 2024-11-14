<?php 

    require_once __DIR__ . "/../user.php";
    
    class UserClienteConcrete extends User {

        public function __construct(string $nomeCompleto, string $email, string $cpf, string $celular, string $sexo, string $senha, string $dataNascimento, string $cep, string $endereco, int $numeroEndereco, string $complemento, string $referencia, string $bairro, $cidade, string $estado) {
            parent::__construct($nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado);
        }

    }

