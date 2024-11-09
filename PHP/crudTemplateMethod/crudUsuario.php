<?php 

require_once __DIR__ . '/../arquivosFactoryMethod/fabricaUser/userConcretCreate.php'; // Caminho corrigido 
require_once __DIR__ . '/crudAbstractTemplateMethod.php';

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
            $sql = "SELECT * FROM Usuario WHERE email = ?";
            
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
                
                // Transformando o registro do usuário encontrado em uma estrutura de par de chave e valor.
                $resultado = $resultado->fetch_assoc();

                // Verificando a senha digitada com a senha armazenada.
                if ($senha === $resultado['senha']) {
            
                    $fabricaUsuario = new UserConcreteCreator();

                    // Instanciando um usuário com os valores retornados da autenticação.
                    $usuarioAutenticado = $fabricaUsuario->criarUsuario($resultado['nomeCompleto'], $resultado['email'], $resultado['cpf'], $resultado['celular'], $resultado['sexo'], $resultado['senha'], $resultado['dataNascimento'], $resultado['cep'], $resultado['endereco'], $resultado['numeroEndereco'], $resultado['complemento'], $resultado['referencia'], $resultado['bairro'], $resultado['cidade'], $resultado['estado'], $resultado['tipoConta']);

                    $usuarioAutenticado->setId($resultado['id']);

                    // Retornando o objeto do usuário autenticado..
                    return $usuarioAutenticado;

                } else { //se a senha digitada não corresponder a senha armazenada no banco.

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
                    $senha = $entidade->getSenha(); 
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

                



            }

           
        }

}