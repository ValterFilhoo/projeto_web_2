<?php 

require_once __DIR__ . '/../arquivosFactoryMethod/fabricaUser/userConcretCreate.php'; // Caminho corrigido 
require_once __DIR__ . '/crudAbstractTemplateMethod.php';

    class CrudUsuario extends CrudTemplateMethod  {

        public function sqlCriar(): string {


            return "INSERT INTO usuario (nomeCompleto, email, cpf, celular, sexo, senha, dataNascimento, cep, endereco, complemento, referencia, bairro, cidade, estado, tipoConta, numeroEndereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        }        

        public function sqlLer(): string {

            return $sql = "SELECT * FROM usuario WHERE id = ?";

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
                    WHERE id = ?";

        }

        public function sqlDeletar(): string {

            return $sql = "DELETE FROM usuario WHERE id = ?";

        }

        public function sqlListar(): string {

            return $sql = "SELECT * FROM usuario";

        }

        public function autenticarUsuario(string $email, string $senha): mixed {

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

                    $this->conexaoBD->close();

                    // Retornando o objeto do usuário autenticado..
                    return $usuarioAutenticado;

                } else { //se a senha digitada não corresponder a senha armazenada no banco.

                    $this->conexaoBD->close();

                    return null;

                }

            } else { // não encontrou nenhum usuário com o email informado.

                $this->conexaoBD->close();

                return null;

            }
        

        }
        
        
        public function vincularParametros(mysqli_stmt $declaracao, object|array|int $entidade, string $operacao): void {

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
                    
                    // Vinculando os parâmetros dos valores da string SQL, passando os tipos dos valores e seus valores.
                    $declaracao->bind_param("ssssssssssssssss", 
                        $nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, 
                        $cep, $endereco, $complemento, $referencia, $bairro, 
                        $cidade, $estado, $tipoConta, $numeroEndereco);
        
                    break;
        
                case "Ler":
                case "Deletar":
                    $id = $entidade; // Para as operações de leitura e exclusão, $entidade é o ID
                    $declaracao->bind_param("i", $id);
                    break;
        
            }

        }
        
        public function obterCaminhoImagemSeNecessario(int $id): never {
            throw new Exception("Esta classe não pode usar este método.");
        }
        
        public function excluirImagemSeExistir(string $caminhoImagem): never {
            throw new Exception("Esta classe não pode usar este método.");
        }

}