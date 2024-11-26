<?php

    require_once __DIR__ . "/../bdSingleton/conexaoBDSingleton.php";
    require_once __DIR__ . "/../bdSingleton/configConexao.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php";
    require_once __DIR__ . "/../arquivosFactoryMethod/pedidoConcrete/pedidoConcrete.php";



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
                    $rows = [];
                    while ($row = $resultadoDaBusca->fetch_assoc()) {
                        $rows[] = $row;
                    }
                    $fabricaConcreta = $this->getFactory($tipo, $rows[0]);
        
                    if (!$fabricaConcreta) {
                        throw new Exception("Tipo de entidade desconhecido: $tipo");
                    }
        
                    // Processar os registros com base no tipo
                    $entidadeEncontrada = $this->processarRegistro($tipo, $fabricaConcreta, $rows);
                    
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

            // Consulta SQL para listar as entidades, e que variam conforme a subclasse.
            $sql = $this->sqlListar();
            $resultadoDaBusca = $this->conexaoBD->query($sql);
        
            if (!$resultadoDaBusca) {

                // Exibe mensagem de erro se a consulta falhar
                echo "Erro na consulta: " . $this->conexaoBD->error;
                return null;

            }
        
            $entidadesEncontradas = [];
        
            if ($resultadoDaBusca->num_rows > 0) {

                while ($linha = $resultadoDaBusca->fetch_assoc()) {

                    // Cria a fábrica concreta com base no tipo de entidade
                    $fabricaConcreta = $this->getFactory($tipo, $linha);
        
                    if (!$fabricaConcreta) {
                        continue; // Ignora se a fábrica não puder ser criada
                    }
        
                    // Processa o registro e adiciona à lista de entidades encontradas
                    $entidadesEncontradas[] = $this->processarRegistro($tipo, $fabricaConcreta, $linha);

                }
        
                // Retorna as entidades encontradas
                return $entidadesEncontradas;

            } else {
                echo 'Nenhuma entidade encontrada.';
                return null;
            }

        }
        
        
        
        protected function processarRegistro($tipo, $fabricaConcreta, $linha) {

            if (empty($linha)) {
                return null; // Retorna null se a linha estiver vazia
            }
        
            // Seleciona o método de processamento com base no tipo de entidade
            switch ($tipo) {

                case 'Produtos':
                    return $this->processarProduto($fabricaConcreta, $linha);
                case 'Usuários':
                    return $this->processarUsuario($fabricaConcreta, $linha);
                case 'Pedidos':
                    $fabricaItemPedido = $this->getFactory('ItensPedido', $linha);
                    $fabricaProduto = $this->getFactory('Produtos', $linha);
                    return $this->processarPedido($fabricaConcreta, $fabricaItemPedido, $fabricaProduto, $linha);
                default:
                    return null; // Retorna null para tipo de entidade desconhecido
                    
            }

        }
        
        
        protected function processarProduto($fabricaConcreta, $linha) {
            
            $dadosProduto = isset($linha[0]) ? $linha[0] : $linha;
        
            // Verificação de chaves presentes nos dados do produto
            $chavesNecessarias = ['id', 'imagemProduto', 'nomeProduto', 'valorProduto', 'quantidade', 'categoria', 'tipoProduto', 'descricaoProduto'];

            foreach ($chavesNecessarias as $chave) {

                if (!isset($dadosProduto[$chave])) {
                    return null;
                }

            }
        
            // Conversão explícita de valores numéricos
            $dadosProduto['valorProduto'] = (float)$dadosProduto['valorProduto'];
            $dadosProduto['quantidade'] = (int)$dadosProduto['quantidade'];
        
            // Cria a entidade do produto usando a fábrica concreta
            $entidade = $fabricaConcreta->factoryMethod(
                $dadosProduto['id'], $dadosProduto['imagemProduto'], $dadosProduto['nomeProduto'], $dadosProduto['valorProduto'], 
                $dadosProduto['quantidade'], $dadosProduto['categoria'], $dadosProduto['tipoProduto'], $dadosProduto['descricaoProduto']
            );
        
            // Retorna um array associativo com os dados do produto
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
        
        
        

        protected function processarPedido($fabricaPedido, $fabricaItemPedido, $fabricaProduto, $linhas) {

            $itensPedido = []; // Inicializa o array de itens do pedido
        
            // Processa cada registro de item no array de registros
            foreach ($linhas as $linha) {

                $dadosItem = is_array($linha) && isset($linha[0]) ? $linha[0] : $linha;
        
                // Verifica se os dados necessários estão presentes
                if (isset($dadosItem['idProduto'], $dadosItem['imagemProduto'], $dadosItem['nomeProduto'], $dadosItem['valorProduto'], $dadosItem['quantidade'], $dadosItem['categoria'], $dadosItem['tipoProduto'], $dadosItem['descricaoProduto'])) {
                    $itemPedido = $this->processarItemPedido($fabricaProduto, $fabricaItemPedido, $dadosItem);
                    $itensPedido[] = $itemPedido;
                }

            }
        
            // Cria a entidade de pedido usando a fábrica concreta de pedidos
            $entidade = $fabricaPedido->criarPedido(
                $linhas[0]['idUsuario'],
                $linhas[0]['dataPedido'],
                $linhas[0]['tipoPagamento'],
                $itensPedido,
                $linhas[0]['valor'],   
                $linhas[0]['chavePix'] ?? null,   
                $linhas[0]['numeroCartao'] ?? null, 
                $linhas[0]['quantidadeParcelas'] ?? null, 
                $linhas[0]['numeroBoleto'] ?? null, 
                $linhas[0]['valorParcelas'] ?? null
            );
        
            // Define o ID da entidade criada
            $entidade->setId($linhas[0]['id']);
        
            // Retorna um array associativo com os dados do pedido e seus itens
            return [
                'id' => $entidade->getId(),
                'idUsuario' => $entidade->getIdUsuario(),
                'dataPedido' => $entidade->getDataPedido(),
                'tipoPagamento' => $entidade->getTipoPagamento(),
                'valor' => $entidade->getValor(),
                'chavePix' => $entidade->getChavePix(),               
                'numeroCartao' => $entidade->getNumeroCartao(),      
                'quantidadeParcelas' => $entidade->getQuantidadeParcelas(), 
                'numeroBoleto' => $entidade->getNumeroBoleto(),
                'valorParcelas' => $entidade->getValorParcelas(),
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
        

        protected function processarItemPedido($fabricaProduto, $fabricaItemPedido, $linha) {

            // Cria o produto usando a fábrica de produtos
            $produto = $fabricaProduto->factoryMethod(
                $linha['idProduto'], $linha['imagemProduto'], $linha['nomeProduto'], $linha['valorProduto'], 
                $linha['quantidade'], $linha['categoria'], $linha['tipoProduto'], $linha['descricaoProduto']
            );
        
            // Cria o item de pedido usando a fábrica de itens de pedido
            $entidade = $fabricaItemPedido->criarItemPedido($produto, $linha['quantidade']);
        
            return $entidade;
            
        }
        
        
        
        
        
        
        

        protected function processarUsuario($fabricaConcreta, $linha) {
            // Desencapsulando os dados caso estejam dentro de um array
            $dadosUsuario = isset($linha[0]) ? $linha[0] : $linha;
        
            // Verificação de chaves presentes nos dados do usuário
            $chavesNecessarias = [
                'id', 'nomeCompleto', 'email', 'cpf', 'celular', 'sexo', 'senha', 
                'dataNascimento', 'cep', 'endereco', 'numeroEndereco', 'complemento', 
                'referencia', 'bairro', 'cidade', 'estado', 'tipoConta'
            ];
            foreach ($chavesNecessarias as $chave) {
                if (!isset($dadosUsuario[$chave])) {
                    echo "Chave faltante: '{$chave}' nos dados: " . json_encode($dadosUsuario);
                    return null;
                }
            }
        
            // Converte os campos apropriados para os tipos corretos
            $numeroEndereco = (int) $dadosUsuario['numeroEndereco']; // Converte para int
        
            // Cria o usuário sem o ID
            $entidade = $fabricaConcreta->criarUsuario(
                $dadosUsuario['nomeCompleto'],
                $dadosUsuario['email'],
                $dadosUsuario['cpf'],
                $dadosUsuario['celular'],
                $dadosUsuario['sexo'],
                $dadosUsuario['senha'],
                $dadosUsuario['dataNascimento'],
                $dadosUsuario['cep'],
                $dadosUsuario['endereco'],
                $numeroEndereco,
                $dadosUsuario['complemento'],
                $dadosUsuario['referencia'],
                $dadosUsuario['bairro'],
                $dadosUsuario['cidade'],
                $dadosUsuario['estado'],
                $dadosUsuario['tipoConta']
            );
        
            // Define o ID usando um método set
            $entidade->setId($dadosUsuario['id']);
        
            // Retorna um array associativo com os dados do usuário
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
        
        
        
        protected function getFactory($tipo, $dados) {

            if ($tipo === 'Produtos') {

                $dadosProduto = isset($dados[0]) ? $dados[0] : $dados;
        
                if (!isset($dadosProduto['categoria'])) {
                    return null;
                }
        
                // Seleciona a fábrica correta com base na categoria do produto
                switch ($dadosProduto['categoria']) {

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
                        return null;
                }

            }
        
            // Seleciona a fábrica correta com base no tipo de entidade
            switch ($tipo) {

                case 'Usuários':
                    return new UserConcreteCreator();
                case 'Pedidos':
                    return new PedidoConcreteCreator();
                case 'ItensPedido':
                    return new ItemPedidoConcreteCreator();
                default:
                    return null;
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
