<?php

require_once __DIR__ . '/../crudTemplateMethod/crudPedido.php';

session_start();

$userId = $_SESSION['id'];

header('Content-Type: application/json');

try {
    $crudPedido = new CrudPedido();

    // Buscar todos os pedidos do usuário
    $pedidos = $crudPedido->listarPedidosPorUsuario($userId);

    echo json_encode(['status' => 'sucesso', 'pedidos' => $pedidos]);
} catch(Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
