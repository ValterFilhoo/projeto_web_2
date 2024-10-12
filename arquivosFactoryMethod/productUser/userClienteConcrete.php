<?php 

    class UserClienteConcrete extends User {

        public function __construct($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado) {
            parent::__construct($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado);
        }

    }

?>