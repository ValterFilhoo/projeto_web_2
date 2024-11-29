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

        public function criarEntidade($entidade): bool {
            
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

        public function lerEntidade(int $id, string $tipo): array|null {
            
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
        

        public function deletarEntidade($id): bool {

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
    
        public function listarEntidades($tipo): array|null{

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
        
                    $entidade = $this->processarRegistro($tipo, $fabricaConcreta, $linha);

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
        
        
        protected function processarRegistro($tipo, $fabricaConcreta, $linha): array|null {
            
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
                    echo "Tipo de entidade desconhecido: $tipo<br>";
                    return null; // Retorna null para tipo de entidade desconhecido
            }

        }
        
        
        // Método que retorna instancias de objetos de produtos concretos.
        protected function processarProduto($fabricaConcreta, $linha): array|null {

            $dadosProduto = isset($linha[0]) ? $linha[0] : $linha;
        
            $chavesNecessarias = ['id', 'imagemProduto', 'nomeProduto', 'valorProduto', 'quantidade', 'categoria', 'tipoProduto', 'descricaoProduto'];
        
            foreach ($chavesNecessarias as $chave) {

                if (!isset($dadosProduto[$chave])) {
                    return null;
                }

            }
        
            $dadosProduto['valorProduto'] = (float)$dadosProduto['valorProduto'];
            $dadosProduto['quantidade'] = (int)$dadosProduto['quantidade'];
        
            $entidade = $fabricaConcreta->criarProduto(
                $dadosProduto['id'], $dadosProduto['imagemProduto'], $dadosProduto['nomeProduto'], $dadosProduto['valorProduto'], 
                $dadosProduto['quantidade'], $dadosProduto['categoria'], $dadosProduto['tipoProduto'], $dadosProduto['descricaoProduto']
            );
        
            $produtoArray = [
                'id' => $entidade->getId(),
                'imagemProduto' => $entidade->getImagem(),
                'nomeProduto' => $entidade->getNome(),
                'valorProduto' => $entidade->getValor(),
                'quantidade' => $entidade->getQuantidade(),
                'categoria' => $entidade->getCategoria(),
                'tipoProduto' => $entidade->getTipo(),
                'descricaoProduto' => $entidade->getDescricao()
            ];
        
            // Se for um kit, adicione os produtos do kit ao array
            if ($dadosProduto['tipoProduto'] === 'Kit' && !empty($dadosProduto['produtosKit'])) {
                $produtosKit = json_decode($dadosProduto['produtosKit'], true);
        
                if (is_array($produtosKit)) {

                    $produtoArray['produtosKit'] = array_map(function($produtoKit) use ($fabricaConcreta) {

                        // Garantir que todos os produtos do kit têm as chaves necessárias
                        $produtoKit = [
                            'id' => isset($produtoKit['id']) ? (int)$produtoKit['id'] : 0,
                            'imagemProduto' => $produtoKit['imagemProduto'] ?? '',
                            'nomeProduto' => $produtoKit['nomeProduto'] ?? '',
                            'valorProduto' => isset($produtoKit['valorProduto']) ? (float)$produtoKit['valorProduto'] : 0.0,
                            'quantidade' => isset($produtoKit['quantidade']) ? (int)$produtoKit['quantidade'] : 0,
                            'categoria' => $produtoKit['categoria'] ?? '',
                            'tipoProduto' => $produtoKit['tipoProduto'] ?? '',
                            'descricaoProduto' => $produtoKit['descricaoProduto'] ?? ''
                        ];
        
                        $produtoKitObj = $fabricaConcreta->criarProduto(
                            $produtoKit['id'], $produtoKit['imagemProduto'], $produtoKit['nomeProduto'], $produtoKit['valorProduto'], 
                            $produtoKit['quantidade'], $produtoKit['categoria'], $produtoKit['tipoProduto'], $produtoKit['descricaoProduto']
                        );
        
                        return [
                            'id' => $produtoKitObj->getId(),
                            'imagemProduto' => $produtoKitObj->getImagem(),
                            'nomeProduto' => $produtoKitObj->getNome(),
                            'valorProduto' => $produtoKitObj->getValor(),
                            'quantidade' => $produtoKitObj->getQuantidade(),
                            'categoria' => $produtoKitObj->getCategoria(),
                            'tipoProduto' => $produtoKitObj->getTipo(),
                            'descricaoProduto' => $produtoKitObj->getDescricao()
                        ];

                    }, $produtosKit);

                } else {
                    $produtoArray['produtosKit'] = [];
                }

            }
        
            return $produtoArray;
        }
        
        
        // Método que retorna objetos do tipo concreto de Pedidos.
        protected function processarPedido($fabricaPedido, $fabricaItemPedido, $fabricaProduto, $linhas): array {
            $itensPedido = []; // Inicializa o array de itens do pedido
        
            // Processa cada registro de item no array de registros
            foreach ($linhas as $linha) {
        
                $dadosItem = is_array($linha) && isset($linha[0]) ? $linha[0] : $linha;
        
                // Seleciona a fábrica correta para o produto
                $fabricaProduto = $this->getFactory('Produtos', $dadosItem);
        
                if (!$fabricaProduto) {
                    echo "Erro ao obter fábrica para produto: " . $dadosItem['nomeProduto'] . "<br>";
                    continue; // Pula o item se a fábrica não for encontrada
                }
        
                // Verifica se os dados necessários estão presentes
                if ($this->dadosNecessariosPresentes($dadosItem)) {
                    $itemPedido = $this->processarItemPedido($fabricaProduto, $fabricaItemPedido, $dadosItem);
        
                    if ($itemPedido) {
                        $itensPedido[] = $itemPedido;
                    } else {
                        echo "Falha ao processar item do pedido: " . json_encode($dadosItem) . "<br>";
                    }
        
                } else {
                    echo "Dados do item incompletos: " . json_encode($dadosItem) . "<br>";
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
                        'idProduto' => $item->getId(),
                        'nomeProduto' => $item->getNome(),
                        'quantidade' => $item->getQuantidade(),
                        'valor' => $item->getValor(),
                        'categoriaProduto' => $item->getCategoria(),
                        'tipoProduto' => $item->getTipo(),
                        'descricaoProduto' => $item->getDescricao(),
                        'imagemProduto' => $item->getImagem()
                    ];
                }, $entidade->getItensPedido())
            ];
        }
        
        
        // Método para verificar se todos os campos da tabela de produto foram retornados no registro.
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
        
        protected function processarItemPedido($fabricaProduto, $fabricaItemPedido, $linha): mixed {

            // Cria o produto usando a fábrica de produtos
            $produto = $fabricaProduto->criarProduto(
                $linha['idProduto'], $linha['imagemProduto'], $linha['nomeProduto'], $linha['valorProduto'], 
                $linha['quantidade'], $linha['categoria'], $linha['tipoProduto'], $linha['descricaoProduto']
            );
        
            // Verifica se a criação do produto foi bem-sucedida
            if (!$produto) {
                echo "Falha ao criar produto.<br>";
                return null;
            }
        
            // Cria o item de pedido usando a fábrica de itens de pedido
            $entidade = $fabricaItemPedido->criarItemPedido($produto, $linha['quantidade']);
        
            // Verifica se a criação do item de pedido foi bem-sucedida
            if (!$entidade) {
                echo "Falha ao criar item de pedido.<br>";
            }
        
            return $entidade;
        }
        
        
        // Método que recebe registros de usuários do banco e retorna um vetor de objetos do tipo User (usuário).
        protected function processarUsuario($fabricaConcreta, $linha): array|null {

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
        
        // Método que dependendo do registro consultado no banco, retorna a fábrica concreta do factory method para instanciar seu objeto.
        protected function getFactory($tipo, $dados): ArduinoConcreteCreator|DisplayConcreteCreator|ItemPedidoConcreteCreator|MotoresConcreteCreator|PedidoConcreteCreator|RaspberryPiConcreteCreator|SensoresConcreteCreator|UserConcreteCreator|null {

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

        abstract public function vincularParametros($declaracao, $entidade, $operacao): void;

        abstract public function obterCaminhoImagemSeNecessario($id); 
        
        abstract public function excluirImagemSeExistir($caminhoImagem);


    }
