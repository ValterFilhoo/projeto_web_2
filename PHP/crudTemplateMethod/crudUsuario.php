<?php 

    require_once "../bdSingleton/instanciarConexaoBD.php";
    require_once __DIR__ . "/./crudAbstractTemplateMethod.php";

    class CrudUsuario extends CrudTemplateMethod  {

        public function sqlCreate($entidade): string {


            // Pegando os dados do objeto Usuário que foi passado como argumento.
            $nomeCompleto = $entidade->getNomeCompleto();
            $email = $entidade->getEmail();
            $cpf = $entidade->getCpf();
            $celular = $entidade->getCelular();
            $sexo = $entidade->getSexo();
            $senha = $entidade->getSenha();
            $dataNascimento = $entidade->getDataNascimento();
            $cep = $entidade->getCep();
            $endereco = $entidade->getEndereco();
            $numeroEndereco = $entidade->getNumeroEndereco();
            $complemento = $entidade->getComplemento();
            $referencia = $entidade->getReferencia();
            $bairro = $entidade->getBairro();
            $cidade = $entidade->getCidade();
            $estado = $entidade->getEstado();
            $tipoConta = $entidade->getTipoConta();

            // Criando a string com a query Insert do usuário no banco e retornando ela.
            return $sql = "INSERT INTO Usuario (nomeCompleto, email, cpf, celular, sexo, senha, dataNascimento, cep, endereco, numeroEndereco, complemento, referencia, bairro, cidade, estado, tipoConta) 
            VALUES ('$nomeCompleto', '$email', '$cpf', '$celular', '$sexo', '$senha', '$dataNascimento', '$cep', '$endereco', '$numeroEndereco', '$complemento', '$referencia', '$bairro', '$cidade', '$estado', '$tipoConta')";


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