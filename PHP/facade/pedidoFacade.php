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

    private $logFile;

    public function __construct() {
        $this->pedidoComposite = new PedidoComposite();
        $this->fabricaPedido = new PedidoConcreteCreator();
        $this->crudPedido = new CrudPedido();
        $this->crudItemPedido = new CrudItemPedido();
        $this->gerenciadorDeFabrica = new GerenciadorDeFabrica();
        $this->logFile = __DIR__ . "/pedido_log.txt";
    }

    private function log($message) {
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    private function criarProdutoConcreto($produtoData) {
        $this->log("Obtendo fábrica para categoria: " . $produtoData['categoria']);
        $fabricaProduto = $this->gerenciadorDeFabrica->obterFabrica($produtoData['categoria']);
        if (!$fabricaProduto) {
            $this->log("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
            throw new Exception("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
        }
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
    
        $this->log("Produto concreto criado: " . json_encode([
            'id' => $produto->getId(),
            'nomeProduto' => $produto->getNome(),
            'categoria' => $produto->getCategoria(),
            'tipoProduto' => $produto->getTipo()
        ]));
    
        return $produto;
    }
    

    public function criarPedido($userId, $dadosPedido, $detalhesPagamento) {
        try {
            $this->crudPedido->iniciarTransacao();
            $this->log("Iniciando transação para o pedido do usuário: $userId");
    
            foreach ($dadosPedido['produtos'] as $produto) {
                $this->log("Adicionando produto ao pedido: " . json_encode($produto));
                $this->adicionarItemAoPedido($produto);
            }
    
            $this->log("Criando estratégia de pagamento...");
            $pagamento = $this->criarEstrategiaPagamento($detalhesPagamento['metodoPagamento'], $detalhesPagamento);
            if ($pagamento === null) {
                throw new Exception('Erro. A estratégia de pagamento está nula.');
            }
            $this->log("Estratégia de pagamento configurada: " . get_class($pagamento));
            
            $this->pedidoComposite->definirFormaPagamento($pagamento);
    
            $valorTotal = $this->pedidoComposite->calcularValorPedido();
            $this->log("Valor total calculado: $valorTotal");
    
            if ($pagamento instanceof CartaoCreditoStrategy) {
                $detalhesPagamento['valorParcelas'] = $pagamento->getValorParcelas();
            }
    
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
    
            $this->crudPedido->criarEntidade($pedido);
            $this->log("Pedido criado com sucesso.");
    
            $idPedido = $this->crudPedido->obterUltimoIdInserido();
            $this->log("ID do pedido obtido: $idPedido");
    
            foreach ($this->pedidoComposite->getItensPedido() as $itemPedido) {
                $itemPedido->setIdPedido($idPedido);
    
                $produto = $itemPedido->getProduto();
                $this->log("Produto do itemPedido: " . get_class($produto));
                if ($produto->getTipo() === 'Kit') {
                    $this->log("Produto é um kit. Obtendo produtos do kit.");
                    $produtosKit = $this->criarProdutosKit($produto->obterProdutos());
                    $this->log("Produtos do kit obtidos: " . json_encode($produtosKit));
    
                    if (empty($produtosKit)) {
                        $this->log("Nenhum produto encontrado no kit.");
                    } else {
                        $produtosKitJson = json_encode($produtosKit);
                        $this->log("Produtos do kit em JSON: $produtosKitJson");
                        $itemPedido->setProdutosKit($produtosKitJson);
                    }
                } else {
                    $this->log("Produto não é um kit.");
                    $itemPedido->setProdutosKit(null);
                }
    
                $this->crudItemPedido->criarEntidade($itemPedido);
            }
    
            $this->crudPedido->commitTransacao();
            $this->log("Transação concluída com sucesso.");
    
            echo nl2br(file_get_contents($this->logFile)); // Exibir o log no navegador
    
            return ["status" => "sucesso", "idPedido" => $idPedido];
    
        } catch (Exception $excecao) {
            $this->crudPedido->rollbackTransacao();
            $this->log("Erro na criação do pedido: " . $excecao->getMessage());
            echo nl2br(file_get_contents($this->logFile)); // Exibir o log no navegador
            return ["status" => "erro", "mensagem" => $excecao->getMessage()];
        }
    }
    
    
    private function adicionarItemAoPedido($produto) {

        $fabrica = $this->gerenciadorDeFabrica->obterFabrica($produto['categoria']);
        $produtosKit = isset($produto['produtosKit']) ? $this->criarProdutosKit($produto['produtosKit']) : [];
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
    
        if ($produtoItem->getId() === -1) {
            $this->log("Produto criado com ID -1: " . json_encode($produtoItem));
        }
    
        $fabricaItemPedido = new ItemPedidoConcreteCreator();
        $itemPedido = $fabricaItemPedido->retornarInstanciaItemPedido($produtoItem, $produto['quantidade']);
        if ($itemPedido instanceof ItemPedidoComponent) {
            $this->pedidoComposite->adicionarItem($itemPedido);
        } else {
            throw new Exception('ItemPedido não é uma instância de ItemPedidoComponent');
        }
        
    }
    
    
    
    
    private function criarProdutosKit($produtos) {
        return array_map(function ($produtoData) {
            $this->log("Obtendo fábrica para categoria: " . $produtoData['categoria']);
            $fabricaProduto = $this->gerenciadorDeFabrica->obterFabrica($produtoData['categoria']);
            if (!$fabricaProduto) {
                $this->log("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
                throw new Exception("Fábrica não encontrada para categoria: " . $produtoData['categoria']);
            }
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
    
            $this->log("Produto concreto criado: " . json_encode([
                'id' => $produto->getId(),
                'nomeProduto' => $produto->getNome(),
                'categoria' => $produto->getCategoria(),
                'tipoProduto' => $produto->getTipo()
            ]));
    
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
    

    private function criarEstrategiaPagamento($metodoPagamento, $detalhesPagamento) {
        $this->log("Iniciando criação da estratégia de pagamento para método: $metodoPagamento");
        if (!isset($metodoPagamento)) {
            throw new Exception('Forma de pagamento inválida.');
        }
    
        $this->log("Dados recebidos para pagamento: " . json_encode($detalhesPagamento));
    
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
                $this->log("Criando objeto CartaoCreditoStrategy...");
                $pagamento = new CartaoCreditoStrategy();
                if ($pagamento === null) {
                    $this->log("Erro ao instanciar CartaoCreditoStrategy.");
                    throw new Exception('Erro ao instanciar CartaoCreditoStrategy.');
                }
                $this->log("CartaoCreditoStrategy instanciada com sucesso.");
    
                if (isset($detalhesPagamento['numeroCartao']) && isset($detalhesPagamento['quantidadeParcelas'])) {
                    $pagamento->setNumeroCartao($detalhesPagamento['numeroCartao']);
                    $pagamento->setQuantidadeParcelas($detalhesPagamento['quantidadeParcelas']);
                    
                    // Definindo a forma de pagamento no PedidoComposite
                    $this->pedidoComposite->definirFormaPagamento($pagamento);
                    $this->log("Forma de pagamento configurada antes de calcular o valor do pedido.");
    
                    // Definindo a porcentagem de desconto
                    $pagamento->setPorcentagemDesconto(0.00); // Desconto padrão para cartão de crédito
                    
                    // Calcular o valor final do pedido
                    $valorTotal = $this->pedidoComposite->calcularValorPedido();
                    $valorFinal = $pagamento->calcularValorFinal($valorTotal);
                    $pagamento->calcularValorDasParcelas($valorFinal);
    
                    $this->log("Valor das parcelas calculado: " . $pagamento->getValorParcelas());
                } else {
                    $this->log("Dados do cartão de crédito incompletos: " . json_encode($detalhesPagamento));
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
        
        if ($pagamento === null) {
            $this->log("Falha na criação da estratégia de pagamento.");
        } else {
            $this->log("Estratégia de pagamento criada com sucesso para método: $metodoPagamento");
        }
    
        return $pagamento;
    }
    
    
    
    
    

}

?>
