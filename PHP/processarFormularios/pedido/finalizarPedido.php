<?php

require_once __DIR__ . '/../../strategy/boletoStrategy.php';
require_once __DIR__ . '/../../strategy/cartaoCreditoStrategy.php';
require_once __DIR__ . '/../../strategy/pixStrategy.php';
require_once __DIR__ . '/../../composite/pedidoComposite.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/produtoCreator.php';
require_once __DIR__ . '/../../encontrarFabricaEspecifica/gerenciadorFabrica.php';

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
$detalhesPagamento = $dadosPedido['detalhesPagamento'];
$produtos = $dadosPedido['produtos'];

// Cria uma instância de PedidoComposite
$pedido = new PedidoComposite();

// Cria uma instância do Gerenciador de Fábricas
$gerenciadorDeFabrica = new GerenciadorDeFabrica();

// Adicionar itens ao pedido utilizando a fábrica correta
foreach ($produtos as $produto) {
    $fabrica = $gerenciadorDeFabrica->obterFabrica($produto['categoria']);
    $produtoItem = $fabrica->criarProduto($produto['id'], $produto['imagemProduto'], $produto['nomeProduto'], $produto['valorProduto'], $produto['quantidade'], $produto['categoria'], $produto['tipoProduto'], $produto['descricaoProduto']);
    $pedido->adicionarItem($produtoItem);
}

// Definir a forma de pagamento
switch ($metodoPagamento) {
    case 'pix':
        $pagamento = new PixStrategy();
        $pagamento->setChavePix($detalhesPagamento['chavePix']);
        $pagamento->setPorcentagemDesconto(0.05); // Desconto para Pix
        break;
    case 'cartao_credito':
        $pagamento = new CartaoCreditoStrategy();
        $pagamento->setNumeroCartao($detalhesPagamento['numeroCartao']);
        $pagamento->setQuantidadeParcelas($detalhesPagamento['quantidadeParcelas']);
        $pagamento->setPorcentagemDesconto(0.00); // Exemplo de desconto para cartão de crédito
        break;
    case 'boleto':
        $pagamento = new BoletoStrategy();
        $pagamento->setNumeroBoleto($detalhesPagamento['numeroBoleto']);
        $pagamento->setPorcentagemDesconto(0.00); // Desconto para boleto
        break;
    default:
        throw new Exception('Forma de pagamento inválida.');
}

$pedido->definirFormaPagamento($pagamento);
$pedido->calcularValorPedido();

$idPedido = $pedido->salvarPedido($conexao, $userId, $metodoPagamento);

echo json_encode(["status" => "sucesso", "idPedido" => $idPedido]);
