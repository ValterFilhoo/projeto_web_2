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
}
