<?php

require_once __DIR__ . "/../../facade/produtoFacade.php";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Variáveis para armazenar os dados do formulário
    $dadosProduto = [
        'nome' => htmlspecialchars($_POST['nome']),
        'quantidade' => intval($_POST['quantidade']),
        'categoria' => htmlspecialchars($_POST['categoria']),
        'tipo' => htmlspecialchars($_POST['tipo']),
        'descricao' => isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '',
        'tipoPrincipal' => isset($_POST['tipoPrincipal']) ? htmlspecialchars($_POST['tipoPrincipal']) : '',
        'kit' => isset($_POST['kit']) ? $_POST['kit'] : [],
        'valor' => isset($_POST['valor']) ? floatval($_POST['valor']) : 0
    ];

    $imagem = $_FILES['imagem'];

    // Instanciar o ProdutoFacade
    $produtoFacade = new ProdutoFacade();

    // Cria o produto usando o facade
    $resultado = $produtoFacade->criarProduto($dadosProduto, $imagem);

    // Exibir o resultado
    header('Content-Type: application/json');
    echo json_encode($resultado);

}
