<?php 

    class CrudUsuario extends CrudTemplateMethod  {

        public function sqlCreate($entidade) {

            return $sql = "INSERT INTO usuario (nome, cpf, celular, telefone, sexo, 
            dataNascimento, cep, endereco, numero, complemento, referencia, bairro, cidade, estado, tipo)
            VALUES ('$entidade->nome', '$entidade->cpf', '$entidade->celular', '$entidade->telefone', '$entidade->sexo', '$entidade->dataNascimento', '$entidade->cep', '$entidade->endereco), '$entidade->numero', '$entidade->complemento', '$entidade->referencia', '$entidade->bairro', '$entidade->cidade', '$entidade->estado', 'usuário'";

        }

        public function sqlRead($id): string {

            return $sql = "SELECT * FROM usuario WHERE idUsuario = $id";

        }

        public function sqlUpdate($id, $entidade): string {

            return  $sql = "UPDATE usuario SET
                    nome = '$entidade->nome',
                    cpf = '$entidade->cpf',
                    celular = '$entidade->celular',
                    telefone = '$entidade->telefone',
                    sexo = '$entidade->sexo',
                    dataNascimento = '$entidade->dataNascimento',
                    cep = '$entidade->cep',
                    endereco = '$entidade->endereco',
                    numero = '$entidade->numero',
                    complemento = '$entidade->complemento',
                    referencia = '$entidade->referencia',
                    bairro = '$entidade->bairro',
                    cidade = '$entidade->cidade',
                    estado = '$entidade->estado',
                    tipo = 'usuário'
                    WHERE idUsuario = $entidade->id";

        }

        public function sqlDelete($id): string {

            return $sql = "DELETE FROM usuario WHERE idUsuario = $id";

        }

    }

?>