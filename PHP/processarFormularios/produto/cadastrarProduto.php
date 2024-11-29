<?php

// Caminhos dos arquivos.
require_once __DIR__ . "/../../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
require_once __DIR__ . "/../../crudTemplateMethod/crudProduto.php";

header('Content-Type: application/json');

try {
    $fabrica;

    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Variáveis para armazenar os dados do formulário
        $imagem = $_FILES['imagem'];
        $nome = htmlspecialchars($_POST['nome']);
        $quantidade = intval($_POST['quantidade']); // Certificando-se que a quantidade seja um inteiro
        $categoria = htmlspecialchars($_POST['categoria']);
        $tipo = htmlspecialchars($_POST['tipo']);
        $descricao = isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : ''; // Garantindo que a descrição seja uma string
        $tipoPrincipal = isset($_POST['tipoPrincipal']) ? htmlspecialchars($_POST['tipoPrincipal']) : '';
        $produtosKit = isset($_POST['kit']['produtos']) ? $_POST['kit']['produtos'] : null; // Produtos do kit, se existirem
        $valor = isset($_POST['valor']) ? floatval($_POST['valor']) : 0; // Valor do produto individual

        // Criar uma array para armazenar os logs
        $logs = [];

        // Adicionar logs dos dados recebidos
        $logs[] = "Tipo do produto principal: $tipoPrincipal";
        $logs[] = "Nome: $nome";
        $logs[] = "Quantidade: $quantidade";
        $logs[] = "Categoria: $categoria";
        $logs[] = "Tipo: $tipo";
        $logs[] = "Descrição: $descricao";
        $logs[] = "Valor: $valor";
        
        // Adicionar logs dos produtos do kit
        if ($produtosKit) {
            foreach ($produtosKit as $index => $produto) {
                $logs[] = "Produto do Kit $index - Nome: {$produto['nome']}, Quantidade: {$produto['quantidade']}, Valor: {$produto['valor']}, Tipo: {$produto['tipo']}";
            }
        }

        // Processamento e validação dos dados
        $erros = [];

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
                switch ($categoria) {
                    case "Arduino": 
                        $fabrica = new ArduinoConcreteCreator();
                        break;
                    case "Display": 
                        $fabrica = new DisplayConcreteCreator();
                        break;
                    case "Motor": 
                        $fabrica = new MotoresConcreteCreator();
                        break;
                    case "RaspberryPI": 
                        $fabrica = new RaspberryPiConcreteCreator();
                        break;
                    case "Sensores":
                        $fabrica = new SensoresConcreteCreator();
                        break;
                    default:
                        throw new Exception("Categoria de produto inválida.");
                }

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
                            -1, '', 
                            $produto['nome'], 
                            $valorProduto, 
                            $quantidadeProduto, 
                            $categoria, 
                            $tipoProduto, 
                            ""
                        );

                        $produtosKitInfo[] = $produtoObj;
                    }

                    // Criar instância do Kit e salvar no banco de dados
                    $kitProduto = $fabrica->criarProduto(
                        -1, $caminhoRelativoBanco, $nome, $valorKit, $quantidade, $categoria, $tipo, $descricao, $produtosKitInfo
                    );

                    $crudProduto = new CrudProduto();
                    if ($crudProduto->criarEntidade($kitProduto)) {
                        $mensagem = "Kit cadastrado com sucesso.";
                    } else {
                        throw new Exception("Erro ao cadastrar o kit no banco de dados.");
                    }
                } else {
                    // Criar um produto individual
                    $produto = $fabrica->criarProduto(
                        -1, $caminhoRelativoBanco, $nome, $valor, $quantidade, $categoria, $tipo, $descricao
                    );
                    $crudProduto = new CrudProduto();
                    if ($crudProduto->criarEntidade($produto)) {
                        $mensagem = "Produto cadastrado com sucesso.";
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
        
        // Exibir mensagem de sucesso junto com os logs
        echo json_encode(["status" => "sucesso", "mensagem" => $mensagem, "logs" => $logs]);

    }

} catch (Exception $excecao) {
    // Capturar e exibir mensagens de erro junto com os logs
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage(), "logs" => $logs]);
}
