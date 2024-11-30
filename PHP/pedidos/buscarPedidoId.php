<?php

require_once __DIR__ . '/../facade/PedidoFacade.php';

header('Content-Type: application/json');

try {
    
    // Verifica se o ID do pedido foi passado como parâmetro
    if (!isset($_GET['id'])) {
        throw new Exception("ID do pedido não especificado.");
    }

    // Converte o parâmetro para um inteiro
    $pedidoId = intval($_GET['id']);
    
    if ($pedidoId <= 0) {
        throw new Exception("ID do pedido inválido.");
    }

    // Instancia o PedidoFacade e chama o método buscarPedidoPorId
    $pedidoFacade = new PedidoFacade();
    $response = $pedidoFacade->buscarPedidoPorId($pedidoId);

    // Retorna a resposta JSON
    echo json_encode($response);

} catch (Exception $e) {
    // Em caso de erro, retorna uma resposta JSON com a mensagem de erro
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
