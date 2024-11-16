<?php

    require_once __DIR__ . "/../bdSingleton/conexaoBDSingleton.php";
    require_once __DIR__ . "/../bdSingleton/configConexao.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";



    abstract class CrudTemplateMethod {

        protected $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_ESCHEMA, BD_PORTA)->getConexao();

        }

        public function criarEntidade($entidade) {
            
            $operacao = "Criar";
            
            // Pegando a parte do método que varia entre as subclasses, nesse caso a string do Insert.
            $sql = $this->sqlCriar();
            
            // Usando o método de preparação da declaração da operação que será feita.
            $stmt = $this->conexaoBD->prepare($sql);

            // Vinculação dos parâmetros dos valores que serão inseridos na tabela do banco de dados.
            $this->vincularParametros($stmt, $entidade, $operacao);
            
            // Pegando o resultado da inserção no banco de dados.
            $resultadoInsert = $stmt->execute();

            // Verificando se realmente cadastrou a entidade no banco.
            // Vai retornar true se a query deu certo.
            if ($resultadoInsert) {

                return true;

            } else {

                echo "Erro no cadastro: " . $stmt->error;
                return false;

            }

        }

        public function lerEntidade($id) {
            
            $operacao = "Ler";
        
            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlLer();
        
            // Preparar a declaração
            if ($stmt = $this->conexaoBD->prepare($sql)) {

                // Vinculação dos parâmetros
                $this->vincularParametros($stmt, $id, $operacao);
        
                // Executar a declaração
                $stmt->execute();
        
                // Obter o resultado
                $resultadoDaBusca = $stmt->get_result();
        
                if ($resultadoDaBusca->num_rows > 0) {
                    $entidadeEncontrada = $resultadoDaBusca->fetch_assoc();
        
                    echo "Entidade encontrada.";
        
                    return $entidadeEncontrada;

                } else {
                    echo 'Entidade não encontrada.';
        
                    return null;
                }

            } else {

                echo "Erro na preparação da declaração: " . $this->conexaoBD->error;
                return null;

            }

        }

        
        public function atualizarEntidade($id, $entidade) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlAtualizar($id, $entidade);

            $resultadoEdicao = $this->conexaoBD->query($sql);

            if ($resultadoEdicao) {
                
                echo 'Entidade editada com sucesso.';

                return true;


            } else {

                echo 'Erro na edição da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }

        public function deletarEntidade($id) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlDeletar($id);

            $resultadoExclusao = $this->conexaoBD->query($sql);

            if ($resultadoExclusao) {
                
                echo 'Entidade excluida com sucesso.';

                return true;


            } else {

                echo 'Erro na exclusão da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }

        public function listarEntidades($tipo) {

            $sql = $this->sqlListar();
            $resultadoDaBusca = $this->conexaoBD->query($sql);
        
            if (!$resultadoDaBusca) {

                echo "Erro na consulta: " . $this->conexaoBD->error;
                return null;

            }
        
            $entidadesEncontradas = [];
        
            if ($resultadoDaBusca->num_rows > 0) {

                while ($row = $resultadoDaBusca->fetch_assoc()) {

                    $fabricaConcreta = $this->getFactory($tipo, $row);

                    if (!$fabricaConcreta) {

                        throw new Exception("Tipo de entidade desconhecido: $tipo");

                    }
        
                    // Processa o registro com base no tipo
                    $entidadesEncontradas[] = $this->processarRegistro($tipo, $fabricaConcreta, $row);

                }
        
                return $entidadesEncontradas; // Retornar as entidades concretas como arrays

            } else {

                echo 'Nenhuma entidade encontrada.';
                return null;

            }

        }
        
        protected function processarRegistro($tipo, $fabricaConcreta, $row) {

            if ($tipo === 'Produtos') {

                return $this->processarProduto($fabricaConcreta, $row);

            } else if ($tipo === 'Usuários') {

                return $this->processarUsuario($fabricaConcreta, $row);

            }

            throw new Exception("Tipo de entidade desconhecido: $tipo");

        }

        
        protected function processarProduto($fabricaConcreta, $row) {

            $entidade = $fabricaConcreta->factoryMethod(
                $row['id'], $row['imagemProduto'], $row['nomeProduto'], $row['valorProduto'], 
                $row['quantidade'], $row['categoria'], $row['tipoProduto'], $row['descricaoProduto']
            );
        
            return [
                'id' => $entidade->getId(),
                'imagemProduto' => $entidade->getImagem(),
                'nomeProduto' => $entidade->getNome(),
                'valorProduto' => $entidade->getValor(),
                'quantidade' => $entidade->getQuantidade(),
                'categoria' => $entidade->getCategoria(),
                'tipoProduto' => $entidade->getTipo(),
                'descricaoProduto' => $entidade->getDescricao()
            ];
        }

        
        protected function processarUsuario($fabricaConcreta, $row) {

            $entidade = $fabricaConcreta->criarUsuario(
                $row['id'], $row['nomeCompleto'], $row['cpf'], $row['celular'], $row['sexo'], 
                $row['email'], $row['senha'], $row['dataNascimento'], $row['cep'], $row['endereco'], 
                $row['numeroEndereco'], $row['complemento'], $row['referencia'], $row['bairro'], 
                $row['cidade'], $row['estado'], $row['tipoConta']
            );
        
            return [
                'id' => $entidade->id,
                'nomeCompleto' => $entidade->nomeCompleto,
                'cpf' => $entidade->cpf,
                'celular' => $entidade->celular,
                'sexo' => $entidade->sexo,
                'email' => $entidade->email,
                'senha' => $entidade->senha,
                'dataNascimento' => $entidade->dataNascimento,
                'cep' => $entidade->cep,
                'endereco' => $entidade->endereco,
                'numeroEndereco' => $entidade->numeroEndereco,
                'complemento' => $entidade->complemento,
                'referencia' => $entidade->referencia,
                'bairro' => $entidade->bairro,
                'cidade' => $entidade->cidade,
                'estado' => $entidade->estado,
                'tipoConta' => $entidade->tipoConta
            ];
        }
        
        
        
        protected function getFactory($tipo, $dados): ProdutoCreator|UserCreator {

            if ($tipo === 'Produtos') {

                // Verificar o subtipo de produto
                switch ($dados['categoria']) {

                    case 'Arduino':
                        return new ArduinoConcreteCreator();
                    case 'Display':
                        return new DisplayConcreteCreator;
                    case 'Motor':
                        return new MotoresConcreteCreator();
                    case 'RaspberryPI':
                        return new RaspberryPiConcreteCreator();
                    case 'Sensores':
                        return new SensoresConcreteCreator();
                    default:
                        throw new Exception("Subtipo de produto desconhecido: " . $dados['tipoProduto']);
                }

            }
        
            // Verificações para outros tipos de entidades
            switch ($tipo) {

                case 'Usuários':
                    return new UserConcreteCreator();
                //case 'pedido':
                   // return new PedidoFactory();
                default:
                    throw new Exception("Tipo de entidade desconhecido: $tipo");
            }

        }
        
        
        

        abstract public function sqlCriar(): string;

        abstract public function sqlLer(): string;

        abstract public function sqlAtualizar(): string;

        abstract public function sqlDeletar(): string;

        abstract public function sqlListar(): string;

        abstract public function vincularParametros($declaracao, $entidade, $operacao): void;


    }
