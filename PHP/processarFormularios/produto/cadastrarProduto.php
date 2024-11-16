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
        $valor = floatval($_POST['valor']); // Certificando-se que o valor seja um float
        $quantidade = intval($_POST['quantidade']); // Certificando-se que a quantidade seja um inteiro
        $categoria = htmlspecialchars($_POST['categoria']);
        $tipo = htmlspecialchars($_POST['tipo']);
        $descricao = htmlspecialchars($_POST['descricao']);

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

                // Instanciar a fábrica concreta de acordo com o a categoria do produto.
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

                // Criar o produto usando a fábrica correta.
                $produto = $fabrica->criarProduto(-1, $caminhoRelativoBanco, $nome, $valor, $quantidade, $categoria, $tipo, $descricao);

                $crudProduto = new CrudProduto();

                if ($crudProduto->criarEntidade($produto)) {

                    $mensagem = "Produto cadastrado com sucesso.";

                } else {

                    throw new Exception("Erro ao cadastrar o produto no banco de dados.");

                }

            } else {

                throw new Exception("Erro ao enviar a imagem.");
                
            }

        } else {
            throw new Exception("Nenhuma imagem enviada.");
        }
        
        // Exibir mensagem de sucesso
        echo json_encode(["status" => "sucesso", "mensagem" => $mensagem]);

    }

} catch (Exception $excecao) {

    // Capturar e exibir mensagens de erro
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);

}