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

// Log File Path
$logFile = __DIR__ . "/pedido_log.txt";

function logMessage($message, $logFile) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    echo nl2br($message . "\n");
}

// Receber os dados do pedido enviados do front-end.
$dadosPedido = json_decode(file_get_contents('php://input'), true);

$userId = $dadosPedido['userId'];
$detalhesPagamento = $dadosPedido['detalhesPagamento'];

// Exibir detalhesPagamento para depuração
logMessage("Detalhes do pagamento: " . json_encode($detalhesPagamento), $logFile);

// Certificar que a chave 'metodoPagamento' está presente
if (!isset($dadosPedido['metodoPagamento'])) {
    $errorMsg = "Forma de pagamento não especificada.";
    logMessage($errorMsg, $logFile);
    echo json_encode(["status" => "erro", "mensagem" => $errorMsg]);
    exit();
}

// Adiciona o método de pagamento ao array detalhesPagamento
$detalhesPagamento['metodoPagamento'] = $dadosPedido['metodoPagamento'];

// Cria uma instância da Facade
$pedidoFacade = new PedidoFacade();

$response = $pedidoFacade->criarPedido($userId, $dadosPedido, $detalhesPagamento);

// Retornar uma resposta JSON com o status e o ID do pedido criado.
logMessage("Resposta: " . json_encode($response), $logFile);
echo json_encode($response);

?>
