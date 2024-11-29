<?php

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo, Brasil.

require_once __DIR__ . '/../../strategy/boletoStrategy.php';
require_once __DIR__ . '/../../strategy/cartaoCreditoStrategy.php';
require_once __DIR__ . '/../../strategy/pixStrategy.php';
require_once __DIR__ . "/../../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . '/../../composite/pedidoComposite.php';
require_once __DIR__ . '/../../encontrarFabricaEspecifica/gerenciadorFabrica.php';
require_once __DIR__ . '/../../crudTemplateMethod/crudPedido.php';
require_once __DIR__ . '/../../crudTemplateMethod/crudItemPedido.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/itemPedidoConcrete/itemPedidoConcrete.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/product.php';
require_once __DIR__ . '/../../facade/pedidoFacade.php'; // Inclui a Facade


// Receber os dados do pedido enviados do front-end.
$dadosPedido = json_decode(file_get_contents('php://input'), true);

$userId = $dadosPedido['userId'];
$detalhesPagamento = $dadosPedido['detalhesPagamento'];


// Certificar que a chave 'metodoPagamento' está presente
if (!isset($dadosPedido['metodoPagamento'])) {

    $erroMsg = "Forma de pagamento não especificada.";

    echo json_encode(["status" => "erro", "mensagem" => $erroMsg]);
    exit();
}

// Adiciona o método de pagamento ao array detalhesPagamento
$detalhesPagamento['metodoPagamento'] = $dadosPedido['metodoPagamento'];

// Cria uma instância da Facade
$pedidoFacade = new PedidoFacade();

$resposta = $pedidoFacade->criarPedido($userId, $dadosPedido, $detalhesPagamento);

// Retornar uma resposta JSON com o status e o ID do pedido criado.
echo json_encode($resposta);

