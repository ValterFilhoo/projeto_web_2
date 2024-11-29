<?php

require_once __DIR__ . "/itemPedidoComponent.php";
require_once __DIR__ . "/../strategy/FormaPagamentoStrategy.php";
require_once __DIR__ . "/../arquivosFactoryMethod/product.php";

class PedidoComposite implements ItemPedidoComponent {

    private $itensPedido = [];
    private ?FormaPagamentoStrategy $pagamentoStrategy = null;
    private float $valorTotalPedido;

    public function adicionarItem(ItemPedidoComponent $item): void {
        $this->itensPedido[] = $item;
    }

    public function removerItem(ItemPedidoComponent $item): bool {
        $indiceDoItem = array_search($item, $this->itensPedido);
        if ($indiceDoItem !== false) {
            unset($this->itensPedido[$indiceDoItem]);
            $this->itensPedido = array_values($this->itensPedido);
            return true;
        }
        return false;
    }

    public function definirFormaPagamento(FormaPagamentoStrategy $strategy): void {
        $this->log("Definindo forma de pagamento: " . get_class($strategy));
        $this->pagamentoStrategy = $strategy;
        if ($this->pagamentoStrategy === null) {
            throw new Exception("Erro. A estratégia de pagamento está nula após atribuição.");
        }
        $this->log("Forma de pagamento definida com sucesso: " . get_class($this->pagamentoStrategy));
    }

    public function calcularValorPedido(): float {
        $valorBasePedido = 0;

        foreach ($this->itensPedido as $item) {
            $valorBasePedido += $item->calcularValorPedido();
        }

        if ($this->pagamentoStrategy !== null) {
            $this->valorTotalPedido = $this->pagamentoStrategy->calcularValorFinal($valorBasePedido);
        } else {
            throw new Exception("Erro. A estratégia de pagamento está nula.");
        }

        return $this->getValorTotalPedido();
    }

    private function log($message): void {
        file_put_contents(__DIR__ . "/pedido_composite_log.txt", date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function getValorTotalPedido(): float {
        return $this->valorTotalPedido;
    }

    public function getItensPedido(): array {
        return $this->itensPedido;
    }

    public function getId(): int {
        throw new Exception("Erro. Este método só pode ser usado pelas classes de kit e produtos.");
    }

    public function getImagem(): string {
        throw new Exception("Erro. Este método só pode ser usado pelas classes de kit e produtos.");
    }

    public function getNome(): string {
        throw new Exception("Erro. Este método só pode ser usado pelas classes de kit e produtos.");
    }

    public function getValor(): float {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit e produtos.");
    }

    public function getQuantidade(): int {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit e produtos.");
    }

    public function getCategoria(): string {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit e produtos.");
    }

    public function getTipo(): string {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit e produtos.");
    }

    public function getDescricao(): string {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit e produtos.");
    }

    public function obterProdutos(): array {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit.");
    }

    public function definirProdutos(array $produtos): void {
        throw new Exception("Erro. Este método em PedidoComposite só pode ser usado pelas classes de kit.");
    }
}
