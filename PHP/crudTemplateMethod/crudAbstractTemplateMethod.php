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

        public function lerEntidade(int $id, string $tipo) {

            $operacao = "Ler";
            
            // Parte do código que varia entre as subclasses.
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

                    $row = $resultadoDaBusca->fetch_assoc();
                    $fabricaConcreta = $this->getFactory($tipo, $row);
        
                    if (!$fabricaConcreta) {
                        throw new Exception("Tipo de entidade desconhecido: $tipo");
                    }
        
                    // Processar o registro com base no tipo
                    $entidadeEncontrada = $this->processarRegistro($tipo, $fabricaConcreta, $row);
                    
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
        
        
        
        
        public function atualizarEntidade($entidade): bool {

            try {

                $operacao = "Atualizar";
        
                // Pegando a parte do método que varia entre as subclasses, nesse caso a string do Update.
                $sql = $this->sqlAtualizar();
        
                // Usando o método de preparação da declaração da operação que será feita.
                $stmt = $this->conexaoBD->prepare($sql);
        
                // Vinculação dos parâmetros dos valores que serão atualizados na tabela do banco de dados.
                $this->vincularParametros($stmt, $entidade, $operacao);
        
                // Pegando o resultado da atualização no banco de dados.
                $resultadoUpdate = $stmt->execute();
        
                // Verificando se realmente atualizou a entidade no banco.
                if ($resultadoUpdate) {

                    return true;

                } else {

                    throw new Exception('Erro na edição da entidade: ' . $stmt->error);

                }

            } catch (Exception $excecao) {

                // Captura e exibe a mensagem de erro
                echo 'Exceção capturada: ' . $excecao->getMessage();
                return false;

            }

        }
        

        public function deletarEntidade($id) {

            $caminhoImagem = null;
        
            try {
                // Parte do código que varia entre as subclasses.
                $caminhoImagem = $this->obterCaminhoImagemSeNecessario($id);
            } catch (Exception $e) {
                // Se ocorrer uma exceção, definimos caminhoImagem como null e continuamos
                $caminhoImagem = null;
            }
        
            // Parte do código que varia entre as subclasses.
            $sql = $this->sqlDeletar(); // Método que retorna a declaração SQL de exclusão
        
            // Preparar a declaração
            if ($stmt = $this->conexaoBD->prepare($sql)) {

                // Vinculação dos parâmetros
                $this->vincularParametros($stmt, $id, "Deletar");
        
                // Executar a declaração
                $resultadoExecucao = $stmt->execute();
        
                if ($resultadoExecucao) {

                    // Exclui a imagem do diretório de imagens do projeto se houver uma
                    if ($caminhoImagem !== null) {

                        try {
                            
                            $this->excluirImagemSeExistir($caminhoImagem);

                        } catch (Exception $e) {
                            // Tratamento de exceção para exclusão de imagem
                            echo 'Erro ao tentar excluir a imagem: ' . $e->getMessage();
                        }

                    }
        
                    return true;

                } else {

                    echo 'Erro na exclusão da entidade: ' . $stmt->error;
                    return false;
                }

            } else {

                echo 'Erro na preparação da declaração: ' . $this->conexaoBD->error;
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
            } else if ($tipo === 'Pedidos') {
                return $this->processarPedido($fabricaConcreta, $row);
            } else if ($tipo === 'ItensPedido') {
                return $this->processarItemPedido($fabricaConcreta, $row);
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

        protected function processarPedido($fabricaConcreta, $row) {
            $itensPedido = []; // Inicialize o array de itens de pedido
        
            foreach ($row['itens'] as $itemRow) {
                $itemPedido = $this->processarItemPedido($fabricaConcreta, $itemRow);
                $itensPedido[] = $itemPedido;
            }
        
            $entidade = $fabricaConcreta->criarPedido(
                $row['idUsuario'],
                $row['dataPedido'],
                $row['tipoPagamento'],
                $itensPedido,
                $row['chavePix'] ?? null,   
                $row['numeroCartao'] ?? null, 
                $row['quantidadeParcelas'] ?? null, 
                $row['numeroBoleto'] ?? null 
            );
        
            $entidade->setId($row['id']);
        
            return [
                'id' => $entidade->getId(),
                'idUsuario' => $entidade->getIdUsuario(),
                'dataPedido' => $entidade->getDataPedido(),
                'tipoPagamento' => $entidade->getTipoPagamento(),
                'chavePix' => $entidade->getChavePix(),               
                'numeroCartao' => $entidade->getNumeroCartao(),      
                'quantidadeParcelas' => $entidade->getQuantidadeParcelas(), 
                'numeroBoleto' => $entidade->getNumeroBoleto(),
                'itens' => array_map(function($item) {
                    return [
                        'idProduto' => $item->getIdProduto(),
                        'nomeProduto' => $item->getNomeProduto(),
                        'quantidade' => $item->getQuantidade(),
                        'valor' => $item->getValor(),
                        'categoriaProduto' => $item->getCategoriaProduto(),
                        'tipoProduto' => $item->getTipoProduto(),
                        'descricaoProduto' => $item->getDescricaoProduto(),
                        'imagemProduto' => $item->getImagemProduto()
                    ];
                }, $entidade->getItensPedido())
            ];

        }
        
        

        protected function processarItemPedido($fabricaConcreta, $row) {

            $produto = $fabricaConcreta->factoryMethod(
                $row['id'], $row['imagemProduto'], $row['nomeProduto'], $row['valorProduto'], 
                $row['quantidade'], $row['categoria'], $row['tipoProduto'], $row['descricaoProduto']
            );
        
            $entidade = $fabricaConcreta->criarItemPedido($produto, $row['quantidade']);
        
            return $entidade;

        }
        

        protected function processarUsuario($fabricaConcreta, $row) {

            // Converte os campos apropriados para os tipos corretos
            $numeroEndereco = (int) $row['numeroEndereco']; // Converte para int
        
            // Cria o usuário sem o ID
            $entidade = $fabricaConcreta->criarUsuario(
                $row['nomeCompleto'],
                $row['email'],
                $row['cpf'],
                $row['celular'],
                $row['sexo'],
                $row['senha'],
                $row['dataNascimento'],
                $row['cep'],
                $row['endereco'],
                $numeroEndereco,
                $row['complemento'],
                $row['referencia'],
                $row['bairro'],
                $row['cidade'],
                $row['estado'],
                $row['tipoConta']
            );
        
            // Define o ID usando um método set
            $entidade->setId($row['id']);
        
            return [
                'id' => $entidade->getId(), // Use o getter aqui
                'nomeCompleto' => $entidade->getNomeCompleto(),
                'cpf' => $entidade->getCpf(),
                'celular' => $entidade->getCelular(),
                'sexo' => $entidade->getSexo(),
                'email' => $entidade->getEmail(),
                'senha' => $entidade->getSenha(),
                'dataNascimento' => $entidade->getDataNascimento(),
                'cep' => $entidade->getCep(),
                'endereco' => $entidade->getEndereco(),
                'numeroEndereco' => $entidade->getNumeroEndereco(),
                'complemento' => $entidade->getComplemento(),
                'referencia' => $entidade->getReferencia(),
                'bairro' => $entidade->getBairro(),
                'cidade' => $entidade->getCidade(),
                'estado' => $entidade->getEstado(),
                'tipoConta' => $entidade->getTipoConta()
            ];

        }
    
        
        protected function getFactory($tipo, $dados): ArduinoConcreteCreator|DisplayConcreteCreator|ItemPedidoConcreteCreator|MotoresConcreteCreator|PedidoConcreteCreator|RaspberryPiConcreteCreator|SensoresConcreteCreator|UserConcreteCreator {

            if ($tipo === 'Produtos') {

                // Verificar o subtipo de produto
                switch ($dados['categoria']) {
                    case 'Arduino':
                        return new ArduinoConcreteCreator();
                    case 'Display':
                        return new DisplayConcreteCreator();
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
                case 'Pedidos':
                    return new PedidoConcreteCreator; // Ajuste conforme sua classe concreta de pedido
                case 'ItensPedido':
                    return new ItemPedidoConcreteCreator; // Ajuste conforme sua classe concreta de item de pedido
                default:
                    throw new Exception("Tipo de entidade desconhecido: $tipo");

            }

        }


        public function iniciarTransacao(): void { 
            $this->conexaoBD->begin_transaction(); 
        } 
        public function commitTransacao(): void {
             $this->conexaoBD->commit(); 
        } 
        public function rollbackTransacao(): void {
             $this->conexaoBD->rollback(); 
        }

        public function obterUltimoIdInserido(): int|string { 
            return $this->conexaoBD->insert_id;
        }

        
        abstract public function sqlCriar(): string;

        abstract public function sqlLer(): string;

        abstract public function sqlAtualizar(): string;

        abstract public function sqlDeletar(): string;

        abstract public function sqlListar(): string;

        abstract public function vincularParametros($declaracao, $entidade, $operacao): void;

        abstract public function obterCaminhoImagemSeNecessario($id); 
        
        abstract public function excluirImagemSeExistir($caminhoImagem);


    }
