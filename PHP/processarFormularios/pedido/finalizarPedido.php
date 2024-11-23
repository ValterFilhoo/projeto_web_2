<?php

require_once __DIR__ . '/../../strategy/boletoStrategy.php';
require_once __DIR__ . '/../../strategy/cartaoCreditoStrategy.php';
require_once __DIR__ . '/../../strategy/pixStrategy.php';
require_once __DIR__ . '/../../composite/pedidoComposite.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/produtoCreator.php';
require_once __DIR__ . '/../../encontrarFabricaEspecifica/gerenciadorFabrica.php';
require_once __DIR__ . '/../../crudTemplateMethod/crudPedido.php';
require_once __DIR__ . '/../../crudTemplateMethod/crudItemPedido.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php';
require_once __DIR__ . '/../../arquivosFactoryMethod/itemPedidoConcrete/itemPedidoConcrete.php';
require_once __DIR__ . '/../../geracoesDeChaves/gerarChavesENumeros.php';

// Receber os dados do pedido enviados do front-end
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

// Criar uma instância de PedidoComposite
$pedidoComposite = new PedidoComposite();

// Criar uma instância do Gerenciador de Fábricas
$gerenciadorDeFabrica = new GerenciadorDeFabrica();

// Criar uma instância de CrudPedido e CrudItemPedido
$crudPedido = new CrudPedido();
$crudItemPedido = new CrudItemPedido();

try {
    // Iniciar transação
    $crudPedido->iniciarTransacao();

    // Adicionar itens ao pedido utilizando a fábrica correta
    foreach ($produtos as $produto) {

        $fabrica = $gerenciadorDeFabrica->obterFabrica($produto['categoria']);
        $produtoItem = $fabrica->criarProduto($produto['id'], $produto['imagemProduto'], $produto['nomeProduto'], $produto['valorProduto'], $produto['quantidade'], $produto['categoria'], $produto['tipoProduto'], $produto['descricaoProduto']);

        // Criar um ItemPedido usando a fábrica parametrizada, passando um objeto Product
        $fabricaItemPedido = new ItemPedidoConcreteCreator();
        $itemPedido = $fabricaItemPedido->factoryMethod($produtoItem, $produto['quantidade']);
        
        // Certifique-se de que $itemPedido seja do tipo ItemPedidoComponent
        if ($itemPedido instanceof ItemPedidoComponent) {
            $pedidoComposite->adicionarItem($itemPedido);
        } else {
            throw new Exception('ItemPedido não é uma instância de ItemPedidoComponent');
        }
    }

    // Definir a forma de pagamento
    switch ($metodoPagamento) {
        
        case 'pix':
            $pagamento = new PixStrategy();
            $chavePix = gerarChavePix();
            $pagamento->setChavePix($chavePix);
            $pagamento->setPorcentagemDesconto(0.05); // Desconto para Pix
            $detalhesPagamento['chavePix'] = $chavePix; // Atualizar detalhes de pagamento
            break;
        case 'cartao_credito':
            $pagamento = new CartaoCreditoStrategy();
            $pagamento->setNumeroCartao($detalhesPagamento['numeroCartao']);
            $pagamento->setQuantidadeParcelas($detalhesPagamento['quantidadeParcelas']);
            $pagamento->setPorcentagemDesconto(0.00); // Exemplo de desconto para cartão de crédito
            break;
        case 'boleto':
            $pagamento = new BoletoStrategy();
            $numeroBoleto = gerarNumeroBoleto();
            $pagamento->setNumeroBoleto($numeroBoleto);
            $pagamento->setPorcentagemDesconto(0.00); // Desconto para boleto
            $detalhesPagamento['numeroBoleto'] = $numeroBoleto; // Atualizar detalhes de pagamento
            break;
        default:
            throw new Exception('Forma de pagamento inválida.');
    }

    $pedidoComposite->definirFormaPagamento($pagamento);
    $valorTotal = $pedidoComposite->calcularValorPedido();

    // Salvar o pedido
    $pedido = $fabricaPedido->criarPedido($userId, date('Y-m-d H:i:s'), $metodoPagamento, $pedidoComposite->getItensPedido());
    $crudPedido->criarEntidade($pedido);

    // Após salvar o pedido, obtemos o ID do pedido recém-criado
    $idPedido = $crudPedido->obterUltimoIdInserido();

    // Salvar os itens do pedido
    foreach ($pedidoComposite->getItensPedido() as $itemPedido) {
        $itemPedido->setIdPedido($idPedido);
        $crudItemPedido->criarEntidade($itemPedido);
    }

    // Commit da transação
    $crudPedido->commitTransacao();

    // Retornar uma resposta JSON com o status e o ID do pedido criado
    echo json_encode(["status" => "sucesso", "idPedido" => $idPedido]);

} catch (Exception $e) {
    // Rollback da transação em caso de erro
    $crudPedido->rollbackTransacao();
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
