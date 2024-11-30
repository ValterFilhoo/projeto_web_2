<?php

    require_once __DIR__ . "/../../facade/ProdutoFacade.php";

    header('Content-Type: application/json');
    session_start();

    try {
        
        // Verificar se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obter os dados do formulário
            $dadosProduto = $_POST;
            $imagem = $_FILES['imagem'] ?? null;

            // Instanciar o facade
            $produtoFacade = new ProdutoFacade();

            // Chamar o método editarProduto do facade
            $resultado = $produtoFacade->editarProduto($dadosProduto, $imagem);

            // Retornar a resposta como JSON
            echo json_encode([
                "status" => $resultado['status'],
                "mensagem" => $resultado['mensagem']
            ]);

        } else {
            throw new Exception("Método de requisição inválido.");
        }

    } catch (Exception $excecao) {

        // Capturar e exibir mensagens de erro
        $erroResposta = [
            "status" => "erro",
            "mensagem" => $excecao->getMessage(),
            "dadosProduto" => isset($dadosProduto) ? $dadosProduto : null
        ];
        echo json_encode($erroResposta);

    }
