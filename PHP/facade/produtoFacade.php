<?php

// Caminhos dos arquivos.
require_once __DIR__ . "/../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";

class ProdutoFacade {
    
    private $crudProduto;

    public function __construct() {
        $this->crudProduto = new CrudProduto();
    }

    // Método para criar um produto
    public function criarProduto($dadosProduto, $imagem): array {

        try {

            // Variáveis para armazenar os dados do formulário
            $nome = htmlspecialchars($dadosProduto['nome']);
            $quantidade = intval($dadosProduto['quantidade']); // Certificando-se que a quantidade seja um inteiro
            $categoria = htmlspecialchars($dadosProduto['categoria']);
            $tipo = htmlspecialchars($dadosProduto['tipo']);
            $descricao = isset($dadosProduto['descricao']) ? htmlspecialchars($dadosProduto['descricao']) : ''; // Garantindo que a descrição seja uma string
            $tipoPrincipal = isset($dadosProduto['tipoPrincipal']) ? htmlspecialchars($dadosProduto['tipoPrincipal']) : '';
            $produtosKit = isset($dadosProduto['kit']['produtos']) ? $dadosProduto['kit']['produtos'] : null; // Produtos do kit, se existirem
            $valor = isset($dadosProduto['valor']) ? floatval($dadosProduto['valor']) : 0; // Valor do produto individual

            // Verificar se uma imagem foi enviada
            if ($imagem['error'] == UPLOAD_ERR_OK) {

                $nomeImagem = basename($imagem['name']);
                $diretorioDestino = __DIR__ . '/../../../img/produtos';
                $caminhoFisicoDestino = $diretorioDestino . '/' . $nomeImagem;
                $caminhoRelativoBanco = 'img/produtos/' . $nomeImagem; // Caminho limpo para salvar no banco de dados

                // Criar o diretório de destino se não existir.
                if (!file_exists($diretorioDestino)) {

                    if (!mkdir($diretorioDestino, 0777, true)) {
                        throw new Exception("Falha ao criar diretório: $diretorioDestino");
                    }

                }

                // Mover a imagem para o diretório de destino
                if (move_uploaded_file($imagem['tmp_name'], $caminhoFisicoDestino)) {

                    // Instanciar a fábrica concreta de acordo com a categoria do produto
                    $fabrica = $this->obterFabrica($categoria);

                    if ($tipoPrincipal === 'Kit' && $produtosKit) {

                        // Calcular o valor do kit com base nos valores dos produtos individuais e suas quantidades
                        $valorKit = 0;
                        $produtosKitInfo = [];

                        foreach ($produtosKit as $produto) {

                            $valorProduto = floatval($produto['valor']);
                            $quantidadeProduto = intval($produto['quantidade']);
                            $tipoProduto = htmlspecialchars($produto['tipo']); // Obter o tipo do produto do kit
                            $descricaoProduto = isset($produto['descricao']) ? htmlspecialchars($produto['descricao']) : '';
                            $valorKit += $valorProduto * $quantidadeProduto;

                            // Criar instâncias dos produtos do kit
                            $produtoObj = $fabrica->criarProduto(
                                -1, '', $produto['nome'], $valorProduto, $quantidadeProduto, $categoria, $tipoProduto, "Produto do Kit de " . $categoria
                            );

                            $produtosKitInfo[] = $produtoObj;
                        }

                        // Criar instância do Kit e salvar no banco de dados
                        $kitProduto = $fabrica->criarProduto(
                            -1, $caminhoRelativoBanco, $nome, $valorKit, $quantidade, $categoria, $tipo, $descricao, $produtosKitInfo
                        );

                        if ($this->crudProduto->criarEntidade($kitProduto)) {
                            return ["status" => "sucesso", "mensagem" => "Kit cadastrado com sucesso."];
                        } else {
                            throw new Exception("Erro ao cadastrar o kit no banco de dados.");
                        }

                    } else {

                        // Criar um produto individual
                        $produto = $fabrica->criarProduto(
                            -1, $caminhoRelativoBanco, $nome, $valor, $quantidade, $categoria, $tipo, $descricao
                        );

                        if ($this->crudProduto->criarEntidade($produto)) {
                            return ["status" => "sucesso", "mensagem" => "Produto cadastrado com sucesso."];
                        } else {
                            throw new Exception("Erro ao cadastrar o produto no banco de dados.");
                        }

                    }

                } else {
                    throw new Exception("Erro ao enviar a imagem.");
                }

            } else {
                throw new Exception("Nenhuma imagem enviada.");
            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }


    // Método para editar um produto existente
    public function editarProduto($dadosProduto, $imagem): array {

        try {

            // Variáveis para armazenar os dados do formulário
            $idProduto = intval($dadosProduto['id']);
            $nome = htmlspecialchars($dadosProduto['nome']);
            $quantidade = intval($dadosProduto['quantidade']);
            $categoria = htmlspecialchars($dadosProduto['categoria']);
            $tipo = htmlspecialchars($dadosProduto['tipo']);
            $descricao = isset($dadosProduto['descricao']) ? htmlspecialchars($dadosProduto['descricao']) : '';
            $tipoPrincipal = isset($dadosProduto['tipoPrincipal']) ? htmlspecialchars($dadosProduto['tipoPrincipal']) : '';
            $produtosKit = isset($dadosProduto['produtosKit']) ? json_decode($dadosProduto['produtosKit'], true) : [];
            $valor = isset($dadosProduto['valor']) ? floatval($dadosProduto['valor']) : 0;
    
            // Verificar se uma nova imagem foi enviada
            if ($imagem && is_array($imagem) && $imagem['error'] == UPLOAD_ERR_OK) {

                $nomeImagemOriginal = basename($imagem['name']);
                $nomeImagem = preg_replace('/[^a-zA-Z0-9-_\.]/', '-', strtolower($nomeImagemOriginal));
                $nomeImagem = str_replace(' ', '-', $nomeImagem);
    
                $diretorioDestino = realpath(__DIR__ . '/../../img/produtos');

                if (!$diretorioDestino) {
                    throw new Exception("Erro ao resolver o caminho do diretório.");
                }

                $caminhoFisicoDestino = $diretorioDestino . '/' . $nomeImagem;
                $caminhoRelativoBanco = 'img/produtos/' . $nomeImagem;
    
                if (!file_exists($diretorioDestino)) {
                    if (!mkdir($diretorioDestino, 0777, true)) {
                        throw new Exception("Falha ao criar diretório: $diretorioDestino");
                    }
                }
    
                if (!is_writable($diretorioDestino)) {
                    throw new Exception("O diretório $diretorioDestino não tem permissão de escrita.");
                }
    
                if (move_uploaded_file($imagem['tmp_name'], $caminhoFisicoDestino)) {

                    if ($dadosProduto['imagemExistente'] && file_exists(realpath(__DIR__ . '/../../../' . $dadosProduto['imagemExistente']))) {

                        if (!unlink(realpath(__DIR__ . '/../../' . $dadosProduto['imagemExistente']))) {
                            throw new Exception("Erro ao excluir a imagem antiga.");
                        }

                    }

                } else {
                    throw new Exception("Erro ao mover a nova imagem para $caminhoFisicoDestino.");
                }

            } else {
                $caminhoRelativoBanco = $dadosProduto['imagemExistente'];
            }
    
            $dadosProduto['imagem'] = $caminhoRelativoBanco;
    
            // Instanciar a fábrica concreta de acordo com a categoria do produto
            $fabrica = $this->obterFabrica($categoria);
    
            if ($tipo === 'Kit' && !empty($produtosKit)) {

                $valorKit = 0;
                $produtosKitInfo = [];
    
                foreach ($produtosKit as $produto) {

                    $valorProduto = floatval($produto['valorProduto']);
                    $quantidadeProduto = intval($produto['quantidade']);
                    $tipoProduto = htmlspecialchars($produto['tipoProduto']);
                    $descricaoProduto = isset($produto['descricao']) ? htmlspecialchars($produto['descricao']) : '';
                    $valorKit += $valorProduto * $quantidadeProduto;
    
                    $produtoObj = $fabrica->criarProduto(
                        -1, 
                        '', 
                        $produto['nomeProduto'], 
                        $valorProduto, 
                        $quantidadeProduto, 
                        $categoria, 
                        $tipoProduto, 
                        "Produto do Kit de " . $categoria
                    );
    
                    $produtosKitInfo[] = $produtoObj;
                }
    
                $kitProduto = $fabrica->criarProduto(
                    $idProduto, 
                    $caminhoRelativoBanco, 
                    $nome, 
                    $valorKit, 
                    $quantidade, 
                    $categoria, 
                    $tipo, 
                    $descricao, 
                    $produtosKitInfo
                );
    
                if ($this->crudProduto->atualizarEntidade($kitProduto)) {
                    return ["status" => "sucesso", "mensagem" => "Kit atualizado com sucesso."];
                } else {
                    throw new Exception("Erro ao atualizar o kit no banco de dados.");
                }

            } else {

                $produto = $fabrica->criarProduto(
                    $idProduto, 
                    $caminhoRelativoBanco, 
                    $nome, 
                    $valor, 
                    $quantidade,
                    $categoria, 
                    $tipo, 
                    $descricao
                );
    
                if ($this->crudProduto->atualizarEntidade($produto)) {
                    return ["status" => "sucesso", "mensagem" => "Produto atualizado com sucesso."];
                } else {
                    throw new Exception("Erro ao atualizar o produto no banco de dados.");
                }

            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }
    
    

    // Método para obter a fábrica correspondente à categoria do produto
    private function obterFabrica($categoria): ArduinoConcreteCreator|DisplayConcreteCreator|MotoresConcreteCreator|RaspberryPiConcreteCreator|SensoresConcreteCreator {
        switch ($categoria) {
            case "Arduino": 
                return new ArduinoConcreteCreator();
            case "Display": 
                return new DisplayConcreteCreator();
            case "Motor": 
                return new MotoresConcreteCreator();
            case "RaspberryPI": 
                return new RaspberryPiConcreteCreator();
            case "Sensores":
                return new SensoresConcreteCreator();
            default:
                throw new Exception("Categoria de produto inválida.");
        }
    }




    // Método para ler um produto pelo ID
    public function lerProdutoPorId($id): array {

        try {
            
            // Verificar se o ID é válido
            if (!isset($id) || !is_int($id)) {
                throw new Exception("ID do produto não especificado ou inválido.");
            }

            // Ler a entidade do produto pelo ID
            $entidade = $this->crudProduto->lerEntidade($id, 'Produtos');

            if ($entidade === null) {
                return ["status" => "erro", "mensagem" => "Entidade não encontrada."];
            } else {

                // Se for um kit, processar os produtos do kit
                if ($entidade['tipoProduto'] === 'Kit' && !empty($entidade['produtosKit'])) {

                    $entidade['produtosKit'] = array_map(function($produto) {
                        return [
                            'id' => $produto['id'] ?? null,
                            'imagemProduto' => $produto['imagemProduto'] ?? null,
                            'nomeProduto' => $produto['nomeProduto'] ?? null,
                            'valorProduto' => $produto['valorProduto'] ?? null,
                            'quantidade' => $produto['quantidade'] ?? null,
                            'categoria' => $produto['categoria'] ?? null,
                            'tipoProduto' => $produto['tipoProduto'] ?? null,
                            'descricaoProduto' => $produto['descricaoProduto'] ?? null
                        ];
                    }, $entidade['produtosKit']);
                }

                return ["status" => "sucesso", "entidade" => $entidade];
            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }


    // Método para buscar produtos por categoria
    public function buscarProdutosPorCategoria($categoria): array {

        try {

            // Verificar se a categoria é válida
            if (!isset($categoria) || empty($categoria)) {
                throw new Exception("Categoria não especificada.");
            }
    
            // Buscar produtos pela categoria
            $produtos = $this->crudProduto->buscarProdutosPorCategoria($categoria);
    
            if (empty($produtos)) {

                return ["status" => "erro", "mensagem" => "Nenhum produto encontrado para a categoria especificada."];
                
            } else {
                // Verificar se os objetos são instâncias da classe Product
                foreach ($produtos as $produto) {
                    if (!$produto instanceof Product) {
                        throw new Exception("Objeto não é uma instância de Product.");
                    }
                }
    
                // Transformar as instâncias dos produtos em arrays antes de retornar
                $produtosArray = array_map(function($produto): array {
                    return [
                        'id' => $produto->getId(),
                        'imagemProduto' => $produto->getImagem(),
                        'nomeProduto' => $produto->getNome(),
                        'valorProduto' => $produto->getValor(),
                        'quantidade' => $produto->getQuantidade(),
                        'categoria' => $produto->getCategoria(),
                        'tipoProduto' => $produto->getTipo(),
                        'descricaoProduto' => $produto->getDescricao()
                    ];
                }, $produtos);
    
                // Verifica se a conta autenticada é "Admin"
                $tipoConta = isset($_SESSION['tipoConta']) ? $_SESSION['tipoConta'] : 'Guest';
    
                return ["status" => "sucesso", "produtos" => $produtosArray, "tipoConta" => $tipoConta];
            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }

    public function listarTodosProdutos($tipo): array {

        try {

            // Verificar se o tipo é válido
            if (!isset($tipo) || empty($tipo)) {
                throw new Exception("Tipo de entidade não especificado.");
            }
    
            // Listar entidades pelo tipo
            $entidades = $this->crudProduto->listarEntidades($tipo);
    
            if (empty($entidades)) {

                return ["status" => "erro", "mensagem" => "Nenhuma entidade encontrada."];

            } else {

                // Verifica se a conta autenticada é "Admin"
                $tipoConta = isset($_SESSION['tipoConta']) ? $_SESSION['tipoConta'] : 'Guest';
    
                // Processa cada entidade para incluir produtos do kit, se necessário
                foreach ($entidades as &$entidade) {

                    // Verifica se a entidade é uma instância de Produto e se é um Kit
                    if ($entidade instanceof Product && $entidade->getTipo() === 'Kit') {

                        // Verifica se a entidade tem os métodos 'obterProdutos' e 'definirProdutos'
                        if (method_exists($entidade, 'obterProdutos') && method_exists($entidade, 'definirProdutos')) {
                            $produtosKit = $entidade->obterProdutos();
    
                            if (is_string($produtosKit)) {
                                $produtosKit = json_decode($produtosKit, true);
                            }
    
                            if (is_array($produtosKit)) {
                                $produtosKitArray = array_map(function($produtoKit) {
                                    return [
                                        'id' => isset($produtoKit['id']) ? (int)$produtoKit['id'] : 0,
                                        'imagemProduto' => $produtoKit['imagemProduto'] ?? '',
                                        'nomeProduto' => $produtoKit['nomeProduto'] ?? '',
                                        'valorProduto' => isset($produtoKit['valorProduto']) ? (float)$produtoKit['valorProduto'] : 0.0,
                                        'quantidade' => isset($produtoKit['quantidade']) ? (int)$produtoKit['quantidade'] : 0,
                                        'categoria' => $produtoKit['categoria'] ?? '',
                                        'tipoProduto' => $produtoKit['tipoProduto'] ?? '',
                                        'descricaoProduto' => $produtoKit['descricaoProduto'] ?? ''
                                    ];
                                }, $produtosKit);
    
                                $entidade->definirProdutos($produtosKitArray);
                            } else {
                                $entidade->definirProdutos([]);
                            }
                        }
                    }
                }
    
                // Converte as entidades em arrays para JSON
                $entidadesArray = array_map(function($entidade) {
                    // Garantir que a entidade é um objeto antes de chamar métodos
                    if (is_object($entidade)) {
                        return [
                            'id' => $entidade->getId(),
                            'imagemProduto' => $entidade->getImagem(),
                            'nomeProduto' => $entidade->getNome(),
                            'valorProduto' => $entidade->getValor(),
                            'quantidade' => $entidade->getQuantidade(),
                            'categoria' => $entidade->getCategoria(),
                            'tipoProduto' => $entidade->getTipo(),
                            'descricaoProduto' => $entidade->getDescricao(),
                            'produtosKit' => method_exists($entidade, 'obterProdutos') ? $entidade->obterProdutos() : []
                        ];

                    } else {
                        return $entidade;
                    }

                }, $entidades);
    
                return ["status" => "sucesso", "entidades" => $entidadesArray, "tipoConta" => $tipoConta];
            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }
    

}
