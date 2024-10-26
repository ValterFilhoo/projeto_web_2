<?php 

    require_once "../bdSingleton/instanciarConexaoBD.php";
    require_once __DIR__ . "/./crudAbstractTemplateMethod.php";

    class CrudUsuario extends CrudTemplateMethod  {

        public function sqlCriar(): string {


            return "INSERT INTO Usuario (nomeCompleto, email, cpf, celular, sexo, senha, dataNascimento, cep, endereco, complemento, referencia, bairro, cidade, estado, tipoConta, numeroEndereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        }        

        public function sqlLer(): string {

            return $sql = "SELECT * FROM usuario WHERE idUsuario = ?";

        }

        public function sqlAtualizar(): string {

            return  $sql = "UPDATE usuario SET
                    nome = ?,
                    cpf = ?
                    celular = ?,
                    telefone = ?,
                    sexo = ?,
                    dataNascimento = ?,
                    cep = ?,
                    endereco = ?,
                    numero = ?,
                    complemento = ?,
                    referencia = ?,
                    bairro = ?,
                    cidade = ?,
                    estado = ?,
                    tipo = ?
                    WHERE idUsuario = ?";

        }

        public function sqlDeletar(): string {

            return $sql = "DELETE FROM Usuario WHERE idUsuario = ?";

        }

        public function sqlListar(): string {

            return $sql = "SELECT * FROM Usuario";

        }

        public function autenticarUsuario(string $email, string $senha) {

            // Verificando primeiro se existe um usuário com aquele email.
            $sql = "SELECT * FROM Usuario WHERE email = $email";

            // Preparando a consulta.
            $stmt = $this->conexaoBD->prepare($sql);

            // Passando o tipo do argumento "?" da string sql e seu valor.
            $stmt->bind_param("s", $email);

            // Executando a operação do Select.
            $stmt->execute();

            // Pegando o resultado da operação.
            $resultado = $stmt->get_result();
            
            // Verificando se encontrou pelo menos um usuário com aquele email cadastrado.
            if ($resultado->num_rows > 0) {

                // Transformando o resultado retornado pelo banco em um array associativo (par de chaves e valores) e atribuindo a uma variável.
                $usuario = $resultado->fetch_assoc();

                // Verificando agora a senha, descriptografando e verificando se é igual a senha digitada no front-end.
                if (password_verify($senha, $usuario['senha'])) {
                    
                    // Retornando o array associativo com os dados do usuário.
                    return $usuario;

                } else {

                    return null;

                }

            } else { // não encontrou nenhum usuário com o email informado.

                return null;

            }
        
            $stmt->close();

            $this->conexaoBD->close();
            
        }
        
        
        public function vincularParametros($declaracao, $entidade, $operacao): void {

            switch ($operacao) {

                case "Criar":

                    $nomeCompleto = $entidade->getNomeCompleto();
                    $email = $entidade->getEmail();
                    $cpf = $entidade->getCpf();
                    $celular = $entidade->getCelular();
                    $sexo = $entidade->getSexo();
                    $senha = password_hash($entidade->getSenha(), PASSWORD_DEFAULT); // Hashing da senha
                    $dataNascimento = $entidade->getDataNascimento();
                    $cep = $entidade->getCep();
                    $endereco = $entidade->getEndereco();
                    $complemento = $entidade->getComplemento();
                    $referencia = $entidade->getReferencia();
                    $bairro = $entidade->getBairro();
                    $cidade = $entidade->getCidade();
                    $estado = $entidade->getEstado();
                    $tipoConta = $entidade->getTipoConta();
                    $numeroEndereco = $entidade->getNumeroEndereco();
                    
                    // Vinculando os parãmetros dos valores da string sql, passando os tipos dos valores e seus valores.
                    $declaracao->bind_param("sssssssssssssssi", 
                        $nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, 
                        $cep, $endereco, $complemento, $referencia, $bairro, 
                        $cidade, $estado, $tipoConta, $numeroEndereco);

                case "Ler":



            }

           
        }

}