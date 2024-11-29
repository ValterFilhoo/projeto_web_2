<?php

require_once __DIR__ . "/../composite/pedidoComposite.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaPedido/pedidoConcreteCreator.php";
require_once __DIR__ . "/../crudTemplateMethod/crudPedido.php";
require_once __DIR__ . "/../crudTemplateMethod/crudItemPedido.php";
require_once __DIR__ . "/../encontrarFabricaEspecifica/gerenciadorFabrica.php";

class PedidoFacade {
    private $pedidoComposite;
    private $fabricaPedido;
    private $crudPedido;
    private $crudItemPedido;
    private $gerenciadorDeFabrica;

    public function __construct() {
        $this->pedidoComposite = new PedidoComposite();
        $this->fabricaPedido = new PedidoConcreteCreator();
        $this->crudPedido = new CrudPedido();
        $this->crudItemPedido = new CrudItemPedido();
        $this->gerenciadorDeFabrica = new GerenciadorDeFabrica();
    }


    // Método para criar um produto concreto a partir dos dados fornecidos
    private function criarProdutoConcreto($produtoData): ItemPedidoComponent {

        // Obtém a fábrica correspondente à categoria do produto
        $fabricaProduto = $this->gerenciadorDeFabrica->obterFabrica($produtoData['categoria']);

        // Verifica se a fábrica foi encontrada
        if (!$fabricaProduto) {
            throw new Exception("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
        }

        // Cria uma instância do produto usando a fábrica
        $produto = $fabricaProduto->retornarInstanciaProduto(
            $produtoData['id'],
            $produtoData['imagemProduto'],
            $produtoData['nomeProduto'],
            $produtoData['valorProduto'],
            $produtoData['quantidade'],
            $produtoData['categoria'],
            $produtoData['tipoProduto'],
            $produtoData['descricaoProduto'],
            isset($produtoData['produtosKit']) ? $produtoData['produtosKit'] : []
        );

        // Verifica se o produto criado é um objeto válido
        if (!is_object($produto)) {
            throw new Exception("Erro ao criar produto concreto: não é um objeto.");
        }

        // Retorna o produto criado
        return $produto;

    }

    // Método para criar um pedido
    public function criarPedido($userId, $dadosPedido, $detalhesPagamento): array {

        try {

            // Inicia uma transação no banco de dados
            $this->crudPedido->iniciarTransacao();

            // Adiciona cada produto ao pedido
            foreach ($dadosPedido['produtos'] as $produto) {
                $this->adicionarItemAoPedido($produto);
            }

            // Cria a estratégia de pagamento com base no método de pagamento fornecido
            $pagamento = $this->criarEstrategiaPagamento($detalhesPagamento['metodoPagamento'], $detalhesPagamento);
            if ($pagamento === null) {
                throw new Exception('Erro. A estratégia de pagamento está nula.');
            }

            // Define a forma de pagamento no pedido
            $this->pedidoComposite->definirFormaPagamento($pagamento);

            // Calcula o valor total do pedido
            $valorTotal = $this->pedidoComposite->calcularValorPedido();

            // Se o pagamento for por cartão de crédito, define o valor das parcelas
            if ($pagamento instanceof CartaoCreditoStrategy) {
                $detalhesPagamento['valorParcelas'] = $pagamento->getValorParcelas();
            }

            // Cria uma instância do pedido usando a fábrica de pedidos
            $pedido = $this->fabricaPedido->criarPedido(
                $userId, 
                date('Y-m-d H:i:s'), 
                $detalhesPagamento['metodoPagamento'], 
                $this->pedidoComposite->getItensPedido(), 
                $valorTotal, 
                $detalhesPagamento['chavePix'] ?? null, 
                $detalhesPagamento['numeroCartao'] ?? null, 
                $detalhesPagamento['quantidadeParcelas'] ?? null, 
                $detalhesPagamento['numeroBoleto'] ?? null, 
                $detalhesPagamento['valorParcelas'] ?? null
            );

            // Salva o pedido no banco de dados
            $this->crudPedido->criarEntidade($pedido);

            // Obtém o ID do pedido recém-criado
            $idPedido = $this->crudPedido->obterUltimoIdInserido();

            // Adiciona cada item do pedido ao banco de dados
            foreach ($this->pedidoComposite->getItensPedido() as $itemPedido) {

                $itemPedido->setIdPedido($idPedido);

                $produto = $itemPedido->getProduto();

                if ($produto->getTipo() === 'Kit') {
                    $produtosKit = $this->criarProdutosKit($produto->obterProdutos());

                    if (empty($produtosKit)) {
                        // Nenhum produto encontrado no kit.
                    } else {
                        $produtosKitJson = json_encode($produtosKit);
                        $itemPedido->setProdutosKit($produtosKitJson);
                    }

                } else {
                    $itemPedido->setProdutosKit(null);
                }

                $this->crudItemPedido->criarEntidade($itemPedido);
            }

            // Confirma a transação no banco de dados
            $this->crudPedido->commitTransacao();

            // Retorna o status de sucesso e o ID do pedido
            return ["status" => "sucesso", "idPedido" => $idPedido];

        } catch (Exception $excecao) {

            // Em caso de erro, desfaz a transação no banco de dados
            $this->crudPedido->rollbackTransacao();
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }

    }


    // Método para adicionar um item ao pedido
    private function adicionarItemAoPedido($produto) {

        // Obtém a fábrica correspondente à categoria do produto
        $fabrica = $this->gerenciadorDeFabrica->obterFabrica($produto['categoria']);
        
        // Cria os produtos do kit, se houver
        $produtosKit = isset($produto['produtosKit']) ? $this->criarProdutosKit($produto['produtosKit']) : [];
        
        // Cria uma instância do produto usando a fábrica
        $produtoItem = $fabrica->retornarInstanciaProduto(
            $produto['id'], 
            $produto['imagemProduto'], 
            $produto['nomeProduto'], 
            $produto['valorProduto'], 
            $produto['quantidade'], 
            $produto['categoria'], 
            $produto['tipoProduto'], 
            $produto['descricaoProduto'], 
            $produtosKit
        );

        // Cria uma instância de ItemPedido usando a fábrica de itens de pedido
        $fabricaItemPedido = new ItemPedidoConcreteCreator();
        $itemPedido = $fabricaItemPedido->retornarInstanciaItemPedido($produtoItem, $produto['quantidade']);
        
        // Adiciona o item ao pedido se for uma instância de ItemPedidoComponent
        if ($itemPedido instanceof ItemPedidoComponent) {
            $this->pedidoComposite->adicionarItem($itemPedido);
        } else {
            throw new Exception('ItemPedido não é uma instância de ItemPedidoComponent');
        }

    }

    // Método para criar produtos do kit
    private function criarProdutosKit($produtos) {

        return array_map(function ($produtoData) {

            // Obtém a fábrica correspondente à categoria do produto
            $fabricaProduto = $this->gerenciadorDeFabrica->obterFabrica($produtoData['categoria']);
            if (!$fabricaProduto) {
                throw new Exception("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
            }
            
            // Cria uma instância do produto usando a fábrica
            $produto = $fabricaProduto->retornarInstanciaProduto(
                $produtoData['id'],
                $produtoData['imagemProduto'],
                $produtoData['nomeProduto'],
                $produtoData['valorProduto'],
                $produtoData['quantidade'],
                $produtoData['categoria'],
                $produtoData['tipoProduto'],
                $produtoData['descricaoProduto'],
                isset($produtoData['produtosKit']) ? $produtoData['produtosKit'] : []
            );

            if (!is_object($produto)) {
                throw new Exception("Erro ao criar produto concreto: não é um objeto.");
            }

            // Retorna os dados do produto em um array
            return [
                'id' => $produto->getId(),
                'imagemProduto' => $produto->getImagem(),
                'nomeProduto' => $produto->getNome(),
                'valorProduto' => $produto->getValor(),
                'quantidade' => $produto->getQuantidade(),
                'categoria' => $produto->getCategoria(),
                'tipoProduto' => $produto->getTipo(),
                'descricaoProduto' => $produto->getDescricao()
            ];

        }, $produtos);

    }

    // Método para criar a estratégia de pagamento
    private function criarEstrategiaPagamento($metodoPagamento, $detalhesPagamento) {

        if (!isset($metodoPagamento)) {
            throw new Exception('Forma de pagamento inválida.');
        }

        $pagamento = null;

        switch ($metodoPagamento) {

            case 'pix':
                
                $pagamento = new PixStrategy();

                if (isset($detalhesPagamento['chavePix'])) {
                    $pagamento->setChavePix($detalhesPagamento['chavePix']);
                    $pagamento->setPorcentagemDesconto(0.05); // Desconto para Pix.
                } else {
                    throw new Exception('Chave Pix não recebida do frontend.');
                }

                break;

            case 'cartao_credito':
                $pagamento = new CartaoCreditoStrategy();

                if ($pagamento === null) {
                    throw new Exception('Erro ao instanciar CartaoCreditoStrategy.');
                }

                if (isset($detalhesPagamento['numeroCartao']) && isset($detalhesPagamento['quantidadeParcelas'])) {
                    $pagamento->setNumeroCartao($detalhesPagamento['numeroCartao']);
                    $pagamento->setQuantidadeParcelas($detalhesPagamento['quantidadeParcelas']);
                    
                    // Define a forma de pagamento no PedidoComposite
                    $this->pedidoComposite->definirFormaPagamento($pagamento);
                    
                    // Define a porcentagem de desconto
                    $pagamento->setPorcentagemDesconto(0.00); // Desconto padrão para cartão de crédito
                    
                    // Calcula o valor final do pedido
                    $valorTotal = $this->pedidoComposite->calcularValorPedido();
                    $valorFinal = $pagamento->calcularValorFinal($valorTotal);
                    $pagamento->calcularValorDasParcelas($valorFinal);
                } else {
                    throw new Exception('Dados do cartão de crédito incompletos.');
                }

                break;
            case 'boleto':

                $pagamento = new BoletoStrategy();

                if (isset($detalhesPagamento['numeroBoleto'])) {
                    $pagamento->setNumeroBoleto($detalhesPagamento['numeroBoleto']);
                    $pagamento->setPorcentagemDesconto(0.00); // Desconto para boleto.
                } else {
                    throw new Exception('Número do boleto não recebido do frontend.');
                }
                break;

            default:
                throw new Exception('Forma de pagamento inválida.');
        }

        return $pagamento;
    }



}

