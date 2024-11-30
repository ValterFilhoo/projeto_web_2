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
    
    private CrudProduto $crudProduto;

    public function __construct() {
        $this->crudProduto = new CrudProduto();
    }

    // Método para criar um produto
   
    public function criarProduto(array $dadosProduto, array $imagem): array {
        try {
            $produto = $this->processarDadosProduto($dadosProduto, $imagem);
    
            if ($this->crudProduto->criarEntidade($produto)) {
                return ["status" => "sucesso", "mensagem" => "Produto cadastrado com sucesso."];
            } else {
                throw new Exception("Erro ao cadastrar o produto no banco de dados.");
            }
        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }
    }
    
    private function processarDadosProduto(array $dadosProduto, array $imagem): ItemPedidoComponent {

        // Variáveis para armazenar os dados do formulário
        $nome = htmlspecialchars($dadosProduto['nome']);
        $quantidade = intval($dadosProduto['quantidade']);
        $categoria = htmlspecialchars($dadosProduto['categoria']);
        $tipo = htmlspecialchars($dadosProduto['tipo']);
        $descricao = isset($dadosProduto['descricao']) ? htmlspecialchars($dadosProduto['descricao']) : '';
        $tipoPrincipal = isset($dadosProduto['tipoPrincipal']) ? htmlspecialchars($dadosProduto['tipoPrincipal']) : '';
        $produtosKit = isset($dadosProduto['kit']['produtos']) ? $dadosProduto['kit']['produtos'] : null;
        $valor = isset($dadosProduto['valor']) ? floatval($dadosProduto['valor']) : 0;
    
        $caminhoRelativoBanco = $this->processarImagem($imagem);
    
        // Instanciar a fábrica concreta de acordo com a categoria do produto
        $fabrica = $this->obterFabrica($categoria);
    
        if ($tipoPrincipal === 'Kit' && $produtosKit) {
            return $this->criarKit($fabrica, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $caminhoRelativoBanco, $produtosKit);
        } else {
            return $fabrica->criarProduto(
                -1, $caminhoRelativoBanco, $nome, $valor, $quantidade, $categoria, $tipo, $descricao
            );

        }

    }
    
    private function processarImagem(array $imagem): string {

        if ($imagem['error'] == UPLOAD_ERR_OK) {

            $nomeImagem = basename($imagem['name']);
            $diretorioDestino = __DIR__ . '/../../img/produtos';
            $caminhoFisicoDestino = $diretorioDestino . '/' . $nomeImagem;
            $caminhoRelativoBanco = 'img/produtos/' . $nomeImagem;
    
            if (!file_exists($diretorioDestino)) {
                if (!mkdir($diretorioDestino, 0777, true)) {
                    throw new Exception("Falha ao criar diretório: $diretorioDestino");
                }
            }
    
            if (move_uploaded_file($imagem['tmp_name'], $caminhoFisicoDestino)) {
                return $caminhoRelativoBanco;
            } else {
                throw new Exception("Erro ao enviar a imagem.");
            }

        } else {
            throw new Exception("Nenhuma imagem enviada.");
        }

    }
    
    private function criarKit(
        $fabrica,
        string $nome,
        float $valor,
        int $quantidade,
        string $categoria,
        string $tipo,
        string $descricao,
        string $caminhoRelativoBanco,
        array $produtosKit
    ): Product {
        $valorKit = 0;
        $produtosKitInfo = [];
    
        foreach ($produtosKit as $produto) {
            $valorProduto = floatval($produto['valor']);
            $quantidadeProduto = intval($produto['quantidade']);
            $tipoProduto = htmlspecialchars($produto['tipo']);
            $descricaoProduto = isset($produto['descricao']) ? htmlspecialchars($produto['descricao']) : '';
            $valorKit += $valorProduto * $quantidadeProduto;
    
            $produtoObj = $fabrica->criarProduto(
                -1, '', $produto['nome'], $valorProduto, $quantidadeProduto, $categoria, $tipoProduto, "Produto do Kit de " . $categoria
            );
    
            $produtosKitInfo[] = $produtoObj;
        }
    
        return $fabrica->criarProduto(
            -1, $caminhoRelativoBanco, $nome, $valorKit, $quantidade, $categoria, $tipo, $descricao, $produtosKitInfo
        );
    }
    

    // Método para editar um produto existente
    public function editarProduto(array $dadosProduto, array $imagem): array {

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

                    if ($dadosProduto['imagemExistente'] && file_exists(realpath(__DIR__ . '/../../' . $dadosProduto['imagemExistente']))) {

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
    

    public function excluirProduto(int $id): array {

        try {

            // Verificar se o ID foi especificado
            if (empty($id)) {
                throw new Exception("ID não especificado.");
            }
    
            // Instanciar a classe que contém os métodos CRUD
            $crudProduto = new CrudProduto();
    
            // Chamar o método deletarEntidade
            $resultado = $crudProduto->deletarEntidade($id);
    
            if ($resultado) {
                return ["status" => "sucesso", "mensagem" => "Produto excluído com sucesso."];
            } else {
                return ["status" => "erro", "mensagem" => "Erro ao excluir o produto."];
            }

        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }
    
    

    // Método para obter a fábrica correspondente à categoria do produto
    private function obterFabrica(string $categoria): ArduinoConcreteCreator|DisplayConcreteCreator|MotoresConcreteCreator|RaspberryPiConcreteCreator|SensoresConcreteCreator {
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
    public function lerProdutoPorId(int $id): array {
        try {
            // Verificar se o ID é válido
            if (!isset($id) || !is_int($id)) {
                throw new Exception("ID do produto não especificado ou inválido.");
            }
    
            // Ler a entidade do produto pelo ID, agora retornando um objeto
            $entidade = $this->crudProduto->lerEntidade($id, 'Produtos');
    
            if ($entidade === null) {
                return ["status" => "erro", "mensagem" => "Entidade não encontrada."];
            } else {
                // Se for um kit, processar os produtos do kit
                if ($entidade instanceof ItemPedidoComponent && $entidade->getTipo() === 'Kit') {
                    // Verifica se a entidade tem os métodos 'obterProdutos' e 'definirProdutos'
                    if (method_exists($entidade, 'obterProdutos') && method_exists($entidade, 'definirProdutos')) {
                        $produtosKit = $entidade->obterProdutos();
    
                        if (is_array($produtosKit)) {
                            // Utilizar o método obterFabrica para criar produtos do kit
                            $produtosKitObjetos = array_map(function($produtoKit) {
                                if (is_object($produtoKit)) {
                                    return $produtoKit; // já é um objeto, então retornamos diretamente
                                } else if (is_array($produtoKit)) {
                                    // Obter a fábrica correta com base na categoria do produto
                                    $fabrica = $this->obterFabrica($produtoKit['categoria']);
                                    // Criar um novo produto usando a fábrica
                                    return $fabrica->criarProduto(
                                        $produtoKit['id'],
                                        $produtoKit['imagemProduto'],
                                        $produtoKit['nomeProduto'],
                                        (float)$produtoKit['valorProduto'],
                                        (int)$produtoKit['quantidade'],
                                        $produtoKit['categoria'],
                                        $produtoKit['tipoProduto'],
                                        $produtoKit['descricaoProduto']
                                    );
                                }
                                throw new Exception("Produto do kit não é um objeto ou array válido.");
                            }, $produtosKit);
    
                            $entidade->definirProdutos($produtosKitObjetos);
                        } else {
                            $entidade->definirProdutos([]);
                        }
                    }
                }
    
                // Converter a entidade em array para resposta JSON
                $entidadeArray = [
                    'id' => $entidade->getId(),
                    'imagemProduto' => $entidade->getImagem(),
                    'nomeProduto' => $entidade->getNome(),
                    'valorProduto' => $entidade->getValor(),
                    'quantidade' => $entidade->getQuantidade(),
                    'categoria' => $entidade->getCategoria(),
                    'tipoProduto' => $entidade->getTipo(),
                    'descricaoProduto' => $entidade->getDescricao(),
                    'produtosKit' => $entidade->getTipo() === 'Kit' ? array_map(function($produto) {
                        // Convertendo objetos para arrays
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
                    }, $entidade->obterProdutos()) : []
                ];
    
                return ["status" => "sucesso", "entidade" => $entidadeArray];
            }
        } catch (Exception $excecao) {
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }
    }
    
    
    // Método para buscar produtos por categoria
    public function buscarProdutosPorCategoria(string $categoria): array {

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

    public function listarTodosProdutos(string $tipo): array {
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
    
                // Converte as entidades em arrays para JSON
                $entidadesArray = array_map(function($entidade) {
                    if (is_object($entidade)) {
                        $produtosKit = [];
                        if ($entidade->getTipo() === 'Kit' && method_exists($entidade, 'obterProdutos')) {
                            $produtosKit = array_map(function($produto) {
                                if (is_object($produto)) {
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
                                }
                                return null;
                            }, $entidade->obterProdutos());
                        }
    
                        return [
                            'id' => $entidade->getId(),
                            'imagemProduto' => $entidade->getImagem(),
                            'nomeProduto' => $entidade->getNome(),
                            'valorProduto' => $entidade->getValor(),
                            'quantidade' => $entidade->getQuantidade(),
                            'categoria' => $entidade->getCategoria(),
                            'tipoProduto' => $entidade->getTipo(),
                            'descricaoProduto' => $entidade->getDescricao(),
                            'produtosKit' => $produtosKit
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
