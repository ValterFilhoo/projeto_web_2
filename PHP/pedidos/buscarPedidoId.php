<?php

require_once __DIR__ . '/../crudTemplateMethod/crudPedido.php';

header('Content-Type: application/json');

try {
    $pedidoId = $_GET['id'];
    $crudPedido = new CrudPedido();

    // Buscar os detalhes do pedido
    $pedido = $crudPedido->lerEntidade($pedidoId, "Pedidos");

    echo json_encode(['status' => 'sucesso', 'pedido' => $pedido]);
} catch(Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
