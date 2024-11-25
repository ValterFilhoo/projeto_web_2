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

// Receber os dados do pedido enviados do front-end.
$dadosPedido = json_decode(file_get_contents('php://input'), true);

$userId = $dadosPedido['userId'];
$nome = $dadosPedido['nome'];
$cpf = $dadosPedido['cpf'];
$email = $dadosPedido['email'];
$telefone = $dadosPedido['telefone'];
$metodoPagamento = $dadosPedido['metodoPagamento'];
$detalhesPagamento = $dadosPedido['detalhesPagamento'];
$produtos = $dadosPedido['produtos'];

$fabricaPedido = new PedidoConcreteCreator();

$pedidoComposite = new PedidoComposite();

$gerenciadorDeFabrica = new GerenciadorDeFabrica();

$crudPedido = new CrudPedido();
$crudItemPedido = new CrudItemPedido();

try {
    // Iniciar transação.
    $crudPedido->iniciarTransacao();

    // Adicionar itens ao pedido utilizando a fábrica correta.
    foreach ($produtos as $produto) {
        $fabrica = $gerenciadorDeFabrica->obterFabrica($produto['categoria']);
        $produtoItem = $fabrica->criarProduto(
            $produto['id'], 
            $produto['imagemProduto'], 
            $produto['nomeProduto'], 
            $produto['valorProduto'], 
            $produto['quantidade'], 
            $produto['categoria'], 
            $produto['tipoProduto'], 
            $produto['descricaoProduto']
        );

        // Criar um ItemPedido usando a fábrica parametrizada, passando um objeto Product.
        $fabricaItemPedido = new ItemPedidoConcreteCreator();
        $itemPedido = $fabricaItemPedido->factoryMethod($produtoItem, $produto['quantidade']);

        // Verificar se o item do pedido é do tipo da interface do composite.
        if ($itemPedido instanceof ItemPedidoComponent) {
            $pedidoComposite->adicionarItem($itemPedido);
        } else {
            throw new Exception('ItemPedido não é uma instância de ItemPedidoComponent');
        }
    }

    // Definir a forma de pagamento primeiro.
    $chavePix = null;
    $numeroCartao = null;
    $quantidadeParcelas = null;
    $numeroBoleto = null;
    $valorParcelas = null;

    switch ($metodoPagamento) {
        case 'pix':
            $pagamento = new PixStrategy();
            if (isset($detalhesPagamento['chavePix'])) {
                $chavePix = $detalhesPagamento['chavePix'];
                $pagamento->setChavePix($chavePix);
                $pagamento->setPorcentagemDesconto(0.05); // Desconto para Pix.
            } else {
                throw new Exception('Chave Pix não recebida do frontend.');
            }
            break;
        case 'cartao_credito':
            $pagamento = new CartaoCreditoStrategy();
            $numeroCartao = $detalhesPagamento['numeroCartao'];
            $quantidadeParcelas = $detalhesPagamento['quantidadeParcelas'];
            $pagamento->setNumeroCartao($numeroCartao);
            $pagamento->setQuantidadeParcelas($quantidadeParcelas);
            $pagamento->setPorcentagemDesconto(0.00);
            break;
        case 'boleto':
            $pagamento = new BoletoStrategy();
            if (isset($detalhesPagamento['numeroBoleto'])) {
                $numeroBoleto = $detalhesPagamento['numeroBoleto'];
                $pagamento->setNumeroBoleto($numeroBoleto);
                $pagamento->setPorcentagemDesconto(0.00); // Desconto para boleto.
            } else {
                throw new Exception('Número do boleto não recebido do frontend.');
            }
            break;
        default:
            throw new Exception('Forma de pagamento inválida.');
    }

    // Definir a forma de pagamento.
    $pedidoComposite->definirFormaPagamento($pagamento);

    // Calcular o valor total após definir a forma de pagamento.
    $valorTotal = $pedidoComposite->calcularValorPedido();

    // Ajustar o cálculo do valorParcelas após definir o valorTotal.
    if ($metodoPagamento === 'cartao_credito') {
        $valorParcelas = $valorTotal / $quantidadeParcelas;
    }

    // Salvar o pedido usando a fábrica do pedido.
    $pedido = $fabricaPedido->criarPedido(
        $userId, 
        date('Y-m-d H:i:s'), 
        $metodoPagamento, 
        $pedidoComposite->getItensPedido(), 
        $valorTotal, 
        $chavePix, 
        $numeroCartao, 
        $quantidadeParcelas, 
        $numeroBoleto, 
        $valorParcelas
    );

    $crudPedido->criarEntidade($pedido);

    // Após salvar o pedido, pega o ID do pedido recém-criado.
    $idPedido = $crudPedido->obterUltimoIdInserido();

    // Salvar os itens do pedido.
    foreach ($pedidoComposite->getItensPedido() as $itemPedido) {
        $itemPedido->setIdPedido($idPedido);
        $crudItemPedido->criarEntidade($itemPedido);
    }

    // Commit da transação.
    $crudPedido->commitTransacao();

    // Retornar uma resposta JSON com o status e o ID do pedido criado.
    echo json_encode(["status" => "sucesso", "idPedido" => $idPedido, "detalhesPagamento" => $detalhesPagamento]);

} catch (Exception $excecao) {
    // Rollback da transação em caso de erro.
    $crudPedido->rollbackTransacao();
    echo json_encode(["status" => "erro", "mensagem" => $excecao->getMessage()]);
}
