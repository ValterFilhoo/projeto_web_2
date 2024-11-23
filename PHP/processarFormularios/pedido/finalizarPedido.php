<?php

require_once __DIR__ . '/../../strategy/boletoStrategy.php';
require_once __DIR__ . '/../../strategy/cartaoCreditoStrategy.php';
require_once __DIR__ . '/../../strategy/pixStrategy.php';
require_once __DIR__ . '/../../strategy/boletoStrategy.php';
require_once __DIR__ . '/../../composite/pedidoComposite.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/produtoCreator.php';

// Suponha que a conexão com o banco de dados esteja em $conexao
$conexao = new mysqli('host', 'username', 'password', 'database');

// Receber os dados do pedido enviado do front end
$dadosPedido = json_decode(file_get_contents('php://input'), true);

$userId = $dadosPedido['userId'];
$nome = $dadosPedido['nome'];
$cpf = $dadosPedido['cpf'];
$email = $dadosPedido['email'];
$telefone = $dadosPedido['telefone'];
$metodoPagamento = $dadosPedido['metodoPagamento'];
$produtos = $dadosPedido['produtos'];

// Cria uma instância de PedidoComposite
$pedido = new PedidoComposite();

// Adicionar itens ao pedido
foreach ($produtos as $produto) {
    $produtoItem = new ProdutoItem($produto['id'], $produto['quantidade'], $produto['valorProduto']);
    $pedido->adicionarItem($produtoItem);
}

// Definir a forma de pagamento
switch ($metodoPagamento) {
    case 'pix':
        $pedido->definirFormaPagamento(new FormaPagamentoPix());
        break;
    case 'cartao_credito':
        $pedido->definirFormaPagamento(new FormaPagamentoCartao());
        break;
    case 'boleto':
        $pedido->definirFormaPagamento(new FormaPagamentoBoleto());
        break;
    default:
        throw new Exception('Forma de pagamento inválida.');
}

// Calcular o valor do pedido
$pedido->calcularValorPedido();

// Salvar o pedido no banco de dados
$idPedido = $pedido->salvarPedido($conexao, $userId, $metodoPagamento);

echo json_encode(["status" => "sucesso", "idPedido" => $idPedido]);

