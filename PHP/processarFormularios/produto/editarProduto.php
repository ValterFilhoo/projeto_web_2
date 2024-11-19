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

    session_start();

    try {
        
        $fabrica;

        // Verificar se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // Variáveis para armazenar os dados do formulário
            $idProduto = intval($_POST['id']); // Certificando-se de que o ID é um inteiro
            $nome = htmlspecialchars($_POST['nome']);
            $valor = floatval($_POST['valor']); // Certifique-se de que o valor é um float
            $quantidade = intval($_POST['quantidade']); // Certifique-se de que a quantidade é um inteiro
            $categoria = htmlspecialchars($_POST['categoria']);
            $tipo = htmlspecialchars($_POST['tipo']);
            $descricao = htmlspecialchars($_POST['descricao']);
            $imagemExistente = isset($_POST['imagemExistente']) ? htmlspecialchars($_POST['imagemExistente']) : ''; // Caminho da imagem existente

            // Verificar se uma nova imagem foi enviada
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {

                $imagem = $_FILES['imagem'];
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

                // Mover a nova imagem para o diretório de destino
                if (move_uploaded_file($imagem['tmp_name'], $caminhoFisicoDestino)) {

                    // Excluir a imagem existente
                    if ($imagemExistente && file_exists(__DIR__ . '/../../../' . $imagemExistente)) {

                        if (!unlink(__DIR__ . '/../../../' . $imagemExistente)) {
                            throw new Exception("Erro ao excluir a imagem antiga.");
                        }

                    }

                } else {
                    throw new Exception("Erro ao enviar a nova imagem.");
                }

            } else {

                // Usar a imagem existente
                $caminhoRelativoBanco = $imagemExistente;
            }

            // Instanciar a fábrica concreta de acordo com a categoria do produto.
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
            $produto = $fabrica->criarProduto($idProduto, $caminhoRelativoBanco, $nome, $valor, $quantidade, $categoria, $tipo, $descricao);

            $crudProduto = new CrudProduto();

            if ($crudProduto->atualizarEntidade($produto)) {

                $mensagem = "Produto editado com sucesso.";

            } else {

                throw new Exception("Erro ao editar o produto no banco de dados.");

            }

            // Exibir mensagem de sucesso
            echo json_encode(["status" => "sucesso", "mensagem" => $mensagem]);

        }

    } catch (Exception $excecao) {
        // Capturar e exibir mensagens de erro
        echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);

    }
