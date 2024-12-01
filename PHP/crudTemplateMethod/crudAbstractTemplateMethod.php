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
    require_once __DIR__ . "/../arquivosFactoryMethod/fabricaUser/userConcretCreate.php";



    abstract class CrudTemplateMethod {

        protected mysqli $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_ESCHEMA, BD_PORTA)->getConexao();

        }

        public function criarEntidade(object|array $entidade): bool {
            
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

        public function lerEntidade(int $id, string $tipo): object|null   {
            
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
        
        
        public function atualizarEntidade(object|array $entidade): bool {

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
        

        public function deletarEntidade(int $id): bool {

            $caminhoImagem = null;
        
            try {

                // Parte do código que varia entre as subclasses.
                $caminhoImagem = $this->obterCaminhoImagemSeNecessario($id);

            } catch (Exception $excecao) {
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

                        } catch (Exception $excecao) {
                            // Tratamento de exceção para exclusão de imagem
                            echo 'Erro ao tentar excluir a imagem: ' . $excecao->getMessage();
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
    
        public function listarEntidades(string $tipo): ?array {

            $sql = $this->sqlListar();
            $resultadoDaBusca = $this->conexaoBD->query($sql);
        
            if (!$resultadoDaBusca) {
                echo "Erro na consulta: " . $this->conexaoBD->error;
                return null;
            }
        
            $entidadesEncontradas = [];
        
            if ($resultadoDaBusca->num_rows > 0) {

                while ($linha = $resultadoDaBusca->fetch_assoc()) {

                    $fabricaConcreta = $this->getFactory($tipo, $linha);
        
                    if (!$fabricaConcreta) {
                        continue;
                    }
        
                    $entidade = $this->processarRegistro($tipo, $fabricaConcreta, [$linha]);
        
                    if ($entidade) {
                        $entidadesEncontradas[] = $entidade;
                    }

                }
        
                return $entidadesEncontradas;

            } else {
                echo 'Nenhuma entidade encontrada.';
                return null;
            }

        }
        
        
        protected function processarRegistro(string $tipo, object $fabricaConcreta, array $dados): ?object {
           
             if (empty($dados)) {
                return null;
            }
        
            switch ($tipo) {

                case 'Produtos':
                    return $this->processarProduto($fabricaConcreta, $dados);
                case 'Usuários':
                    return $this->processarUsuario($fabricaConcreta, $dados);
                case 'Pedidos':
                    $fabricaItemPedido = $this->getFactory('ItensPedido', $dados);
                    $fabricaProduto = $this->getFactory('Produtos', $dados);
                    return $this->processarPedido($fabricaConcreta, $fabricaItemPedido, $fabricaProduto, $dados);
                default:
                    echo "Tipo de entidade desconhecido: $tipo<br>";
                    return null;
            }

        }
        
        
        // Método que retorna instancias de objetos de produtos concretos.
        protected function processarProduto(object $fabricaConcreta, array $dados): ?ItemPedidoComponent {

            $dadosProduto = isset($dados[0]) ? $dados[0] : $dados;
        
            $chavesNecessarias = ['id', 'imagemProduto', 'nomeProduto', 'valorProduto', 'quantidade', 'categoria', 'tipoProduto', 'descricaoProduto'];
        
            foreach ($chavesNecessarias as $chave) {

                if (!isset($dadosProduto[$chave])) {
                    return null;
                }

            }
        
            $dadosProduto['valorProduto'] = (float)$dadosProduto['valorProduto'];
            $dadosProduto['quantidade'] = (int)$dadosProduto['quantidade'];
        
            $produto = $fabricaConcreta->criarProduto(
                $dadosProduto['id'], $dadosProduto['imagemProduto'], $dadosProduto['nomeProduto'], $dadosProduto['valorProduto'], 
                $dadosProduto['quantidade'], $dadosProduto['categoria'], $dadosProduto['tipoProduto'], $dadosProduto['descricaoProduto']
            );
        
            if ($dadosProduto['tipoProduto'] === 'Kit' && !empty($dadosProduto['produtosKit'])) {

                $produtosKit = json_decode($dadosProduto['produtosKit'], true);
        
                if (is_array($produtosKit)) {

                    $produtosKitObjetos = array_map(function(array $produtoKit) use ($fabricaConcreta): ItemPedidoComponent {
                        return $fabricaConcreta->criarProduto(
                            $produtoKit['id'], $produtoKit['imagemProduto'], $produtoKit['nomeProduto'], $produtoKit['valorProduto'], 
                            $produtoKit['quantidade'], $produtoKit['categoria'], $produtoKit['tipoProduto'], $produtoKit['descricaoProduto']
                        );
                    }, $produtosKit);
        
                    if (method_exists($produto, 'definirProdutos')) {
                        $produto->definirProdutos($produtosKitObjetos);
                    } else {
                        echo "A classe " . get_class($produto) . " não possui o método definirProdutos.<br>";
                    }

                }
            }
        
            return $produto;
        }
        
        
        
        // Método que retorna objetos do tipo concreto de Pedidos.

        protected function processarPedido(object $faPedido, object $faItem, object $faProduto, array $dados): ?Pedido {

            $itensPedido = [];
            $idUsuario = null;
            $dataPedido = null;
            $tipoPagamento = null;
            $valorTotal = 0.0;
            $chavePix = null;
            $numeroCartao = null;
            $quantidadeParcelas = null;
            $numeroBoleto = null;
            $valorParcelas = null;
            
            // Processa cada registro de item nos dados fornecidos
            foreach ($dados as $linha) {

                $dadosItem = is_array($linha) && isset($linha[0]) ? $linha[0] : $linha;
                $faProduto = $this->getFactory('Produtos', $dadosItem);
            
                // Verifica se a fábrica do produto foi encontrada
                if (!$faProduto) {
                    echo "Erro ao obter fábrica para produto: " . $dadosItem['nomeProduto'] . "<br>";
                    continue;
                }
            
                // Verifica se todos os dados necessários estão presentes
                if ($this->dadosNecessariosPresentes($dadosItem)) {
                    
                    $itemPedido = $this->processarItemPedido($faProduto, $faItem, $dadosItem);
            
                    if ($itemPedido) {

                        // Atribui os produtos do kit se o item for um kit
                        if ($itemPedido instanceof ItemPedidoKit && isset($dadosItem['produtosKit'])) {
                                
                            $produtosKit = json_decode($dadosItem['produtosKit'], true);
                                
                            if (is_array($produtosKit) && !empty($produtosKit)) {
                                    $itemPedido->definirProdutos($produtosKit);
                            } else {
                                echo "Produtos do kit estão vazios ou não são um array válido para o produto: " . $dadosItem['nomeProduto'] . "<br>";
                            }

                        }

                        $itensPedido[] = $itemPedido;
            
                        // Define os atributos do pedido, se ainda não estiverem definidos
                        $idUsuario = $idUsuario ?? intval($dadosItem['idUsuario'] ?? null);
                        $dataPedido = $dataPedido ?? $dadosItem['dataPedido'] ?? null;
                        $tipoPagamento = $tipoPagamento ?? $dadosItem['tipoPagamento'] ?? null;
                        $valorTotal += floatval($dadosItem['valorItem'] ?? 0) * $itemPedido->getQuantidade();
                        $chavePix = $chavePix ?? $dadosItem['chavePix'] ?? null;
                        $numeroCartao = $numeroCartao ?? $dadosItem['numeroCartao'] ?? null;
                        $quantidadeParcelas = $quantidadeParcelas ?? intval($dadosItem['quantidadeParcelas'] ?? null);
                        $numeroBoleto = $numeroBoleto ?? $dadosItem['numeroBoleto'] ?? null;
                        $valorParcelas = $valorParcelas ?? floatval($dadosItem['valorParcelas'] ?? null);

                    } else {
                        echo "Falha ao processar item do pedido: " . json_encode($dadosItem) . "<br>";
                    }

                } else {
                    echo "Dados do item incompletos: " . json_encode($dadosItem) . "<br>";
                }

            }
            
            // Verifica se todos os atributos necessários do pedido foram definidos
            if ($idUsuario === null || $dataPedido === null || $tipoPagamento === null) {
                echo "Atributos do pedido incompletos.";
                return null;
            }
            
                // Cria o pedido e define o ID
            $pedido = $faPedido->criarPedido(
                $idUsuario,
                $dataPedido,
                $tipoPagamento,
                $itensPedido,
                $valorTotal,
                $chavePix,
                $numeroCartao,
                $quantidadeParcelas,
                $numeroBoleto,
                $valorParcelas
            );
            
            if ($pedido instanceof PedidoConcrete) {
                $pedido->setId($this->obterUltimoIdInserido());
            }
            
            return $pedido;
        }
            

        // Método para verificar se todos os campos da tabela de produto foram retornados no registro
        protected function dadosNecessariosPresentes(array $dadosItem): bool {

            return isset(
                $dadosItem['idProduto'],
                $dadosItem['imagemProduto'],
                $dadosItem['nomeProduto'],
                $dadosItem['valorProduto'],
                $dadosItem['quantidade'],
                $dadosItem['categoria'],
                $dadosItem['tipoProduto'],
                $dadosItem['descricaoProduto']
            );

        }

        // Método para processar cada item do pedido
        protected function processarItemPedido(object $fabricaProduto, ItemPedidoConcreteCreator $fabricaItemPedido, array $dados): ?ItemPedidoComponent {

            $produto = $this->processarProduto($fabricaProduto, $dados);
        
            if ($produto === null) {
                return null;
            }
        
            $quantidade = isset($dados['quantidade']) ? intval($dados['quantidade']) : 1;
        
            // Uso da fábrica para criar o item do pedido
            $itemPedido = $fabricaItemPedido->criarItemPedido($produto, $quantidade);
        
            // Atribui os produtos do kit se o item for um kit e os produtos estiverem presentes
            if ($itemPedido instanceof ItemPedidoKit && isset($dados['produtosKit'])) {

                $produtosKit = json_decode($dados['produtosKit'], true);

                if (is_array($produtosKit) && !empty($produtosKit)) {
                    $itemPedido->definirProdutos($produtosKit);
                }
            }
        
            return $itemPedido;
        }
        
        // Método que recebe registros de usuários do banco e retorna um vetor de objetos do tipo User (usuário).
        protected function processarUsuario(UserCreator $fabricaUsuario, array $dados): ?User {

            // Desencapsulando os dados caso estejam dentro de um array
            $dadosUsuario = isset($dados[0]) ? $dados[0] : $dados;
        
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
            $entidade = $fabricaUsuario->criarUsuario(
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
        
            return $entidade;
        }
        
        
        // Método que dependendo do registro consultado no banco, retorna a fábrica concreta do factory method para instanciar seu objeto.
        protected function getFactory(string $tipo, array $dados): ArduinoConcreteCreator|DisplayConcreteCreator|ItemPedidoConcreteCreator|MotoresConcreteCreator|PedidoConcreteCreator|RaspberryPiConcreteCreator|SensoresConcreteCreator|UserConcreteCreator|null {

            if ($tipo === 'Produtos') {
                $dadosProduto = isset($dados[0]) ? $dados[0] : $dados;
        
                if (!isset($dadosProduto['categoria'])) {
                    echo "Categoria não definida em dadosProduto.<br>";
                    return null;
                }
        
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
                        echo "Categoria desconhecida: " . $dadosProduto['categoria'] . "<br>";
                        return null;
                }

            }
        
            switch ($tipo) {

                case 'Usuários':
                    return new UserConcreteCreator();
                case 'Pedidos':
                    return new PedidoConcreteCreator();
                case 'ItensPedido':
                case 'pedido_produto':
                    return new ItemPedidoConcreteCreator();
                default:
                    echo "Tipo de entidade desconhecido: $tipo<br>";
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

        abstract public function vincularParametros(mysqli_stmt $declaracao, object|array|int $entidade, string $operacao): void;

        abstract public function obterCaminhoImagemSeNecessario(int $id): string|null; 
        
        abstract public function excluirImagemSeExistir(string $caminhoImagem): void;


    }
