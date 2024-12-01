<?php

require_once __DIR__ . "/../composite/pedidoComposite.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaPedido/pedidoConcreteCreator.php";
require_once __DIR__ . "/../crudTemplateMethod/crudPedido.php";
require_once __DIR__ . "/../crudTemplateMethod/crudItemPedido.php";
require_once __DIR__ . "/../encontrarFabricaEspecifica/gerenciadorFabrica.php";

class PedidoFacade {

    private PedidoComposite $pedidoComposite;
    private PedidoConcreteCreator $fabricaPedido;
    private CrudPedido $crudPedido;
    private CrudItemPedido $crudItemPedido;
    private GerenciadorDeFabrica $gerenciadorDeFabrica;

    public function __construct() {
        $this->pedidoComposite = new PedidoComposite();
        $this->fabricaPedido = new PedidoConcreteCreator();
        $this->crudPedido = new CrudPedido();
        $this->crudItemPedido = new CrudItemPedido();
        $this->gerenciadorDeFabrica = new GerenciadorDeFabrica();
    }


    // Método para criar um pedido
    public function criarPedido(int $userId, array $dadosPedido, array $detalhesPagamento): array {

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
            $pedido = $this->criarInstanciaPedido($userId, $detalhesPagamento, $valorTotal);
    
            // Salva o pedido no banco de dados
            $this->crudPedido->criarEntidade($pedido);
    
            // Obtém o ID do pedido recém-criado
            $idPedido = $this->crudPedido->obterUltimoIdInserido();
    
            // Adiciona cada item do pedido ao banco de dados
            $this->adicionarItensAoBanco($idPedido);
    
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
    
    private function criarInstanciaPedido(int $userId, array $detalhesPagamento, float $valorTotal): Pedido {

        return $this->fabricaPedido->criarPedido(
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

    }
    
    private function adicionarItensAoBanco(int $idPedido): void {

        foreach ($this->pedidoComposite->getItensPedido() as $itemPedido) {

            $itemPedido->setIdPedido($idPedido);
    
            $produto = $itemPedido->getProduto();
    
            if ($produto->getTipo() === 'Kit') {

                $produtosKit = $this->criarProdutosKit($produto->obterProdutos());
    
                if (empty($produtosKit)) {
                    // Nenhum produto encontrado no kit.
                    $itemPedido->setProdutosKit(null);
                } else {
                    $produtosKitJson = json_encode($produtosKit);
                    $itemPedido->setProdutosKit($produtosKitJson);
                }

            } else {
                $itemPedido->setProdutosKit(null);
            }
    
            $this->crudItemPedido->criarEntidade($itemPedido);
        }
    }
    

    // Método para adicionar um item ao pedido
    private function adicionarItemAoPedido(array $produto): void {

        // Obtém a fábrica correspondente à categoria do produto
        $fabrica = $this->gerenciadorDeFabrica->obterFabrica($produto['categoria']);
        
        // Cria os produtos do kit, se houver
        $produtosKit = isset($produto['produtosKit']) ? $this->criarProdutosKit($produto['produtosKit']) : [];
        
        // Cria uma instância do produto usando a fábrica
        $produtoItem = $fabrica->criarProduto(
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
        $itemPedido = $fabricaItemPedido->criarItemPedido($produtoItem, $produto['quantidade']);
        
        // Adiciona o item ao pedido se for uma instância de ItemPedidoComponent
        if ($itemPedido instanceof ItemPedidoComponent) {
            $this->pedidoComposite->adicionarItem($itemPedido);
        } else {
            throw new Exception('ItemPedido não é uma instância de ItemPedidoComponent');
        }

    }

    // Método para criar produtos do kit
    private function criarProdutosKit(array $produtos): array {

        return array_map(function ($produtoData): array {

            // Obtém a fábrica correspondente à categoria do produto
            $fabricaProduto = $this->gerenciadorDeFabrica->obterFabrica($produtoData['categoria']);
            if (!$fabricaProduto) {
                throw new Exception("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
            }
            
            // Cria uma instância do produto usando a fábrica
            $produto = $fabricaProduto->criarProduto(
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
    private function criarEstrategiaPagamento(string $metodoPagamento, array $detalhesPagamento): BoletoStrategy|CartaoCreditoStrategy|PixStrategy {

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


    public function buscarPedidoPorId(int $pedidoId): array {

        try {
            
            $pedido = $this->crudPedido->lerEntidade($pedidoId, "Pedidos");
    
            if ($pedido === null) {
                return ['status' => 'erro', 'mensagem' => 'Pedido não encontrado.'];
            }
    
            $itensPedido = $pedido->getItensPedido();
            $itensArray = array_map(function($item): array {
                $itemArray = [
                    'idProduto' => $item->getProduto()->getId(),
                    'nomeProduto' => $item->getProduto()->getNome(),
                    'imagemProduto' => $item->getProduto()->getImagem(),
                    'tipoProduto' => $item->getProduto()->getTipo(),
                    'quantidade' => $item->getQuantidade(),
                    'valor' => $item->getValor(),
                    'categoria' => $item->getProduto()->getCategoria()
                ];
    
                if ($item instanceof ItemPedidoKit) {

                    $produtosKit = $item->obterProdutos();

                    if (!empty($produtosKit)) {
                        $itemArray['produtosKit'] = array_map(function($produto) {
                            return [
                                'idProduto' => $produto['id'] ?? null,
                                'imagemProduto' => $produto['imagemProduto'] ?? null,
                                'nomeProduto' => $produto['nomeProduto'] ?? null,
                                'valorProduto' => $produto['valorProduto'] ?? null,
                                'quantidade' => $produto['quantidade'] ?? null,
                                'categoria' => $produto['categoria'] ?? null,
                                'tipoProduto' => $produto['tipoProduto'] ?? null,
                                'descricaoProduto' => $produto['descricaoProduto'] ?? null
                            ];
                        }, $produtosKit);

                    } else {
                        $itemArray['produtosKit'] = [];
                    }

                } else {
                    $itemArray['produtosKit'] = null; // Assegurar que produtosKit seja null para itens que não são kits
                }
    
                return $itemArray;
            }, $itensPedido);
    
            return [
                'status' => 'sucesso',
                'pedido' => [
                    'id' => $pedido->getId(),
                    'dataPedido' => $pedido->getDataPedido(),
                    'tipoPagamento' => $pedido->getTipoPagamento(),
                    'chavePix' => $pedido->getChavePix(),
                    'numeroCartao' => $pedido->getNumeroCartao(),
                    'quantidadeParcelas' => $pedido->getQuantidadeParcelas(),
                    'numeroBoleto' => $pedido->getNumeroBoleto(),
                    'valor' => $pedido->getValor(),
                    'valorParcelas' => $pedido->getValorParcelas(),
                    'itens' => $itensArray
                ]
            ];
    
        } catch (Exception $e) {
            return ['status' => 'erro', 'mensagem' => $e->getMessage()];
        }

    }
    
    
}