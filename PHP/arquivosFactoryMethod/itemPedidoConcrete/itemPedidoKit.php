<?php

require_once __DIR__ . '/../product.php';
require_once __DIR__ . '/../../composite/itemPedidoComponent.php';

class ItemPedidoKit implements ItemPedidoComponent {

    private int $idPedido;
    private ItemPedidoComponent $produto;
    private int $quantidade;
    private ?string $produtosKit;  // Pode ser uma string JSON ou null

    public function __construct(ItemPedidoComponent $produto, int $quantidade) {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->produtosKit = null;
    }

    public function setProduto(ItemPedidoComponent $produto): void {
        $this->produto = $produto;
    }

    public function setIdPedido(int $idPedido): void {
        $this->idPedido = $idPedido;
    }

    public function getProduto(): ItemPedidoComponent {
        return $this->produto;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }

    public function getIdPedido(): int {
        return $this->idPedido;
    }

    public function getId(): int {
        return $this->produto->getId();
    }

    public function getNome(): string {
        return $this->produto->getNome();
    }

    public function getValor(): float {
        return $this->produto->getValor();
    }

    public function getCategoria(): string {
        return $this->produto->getCategoria();
    }

    public function getTipo(): string {
        return $this->produto->getTipo();
    }

    public function getDescricao(): string {
        return $this->produto->getDescricao();
    }

    public function getImagem(): string {
        return $this->produto->getImagem();
    }

    public function calcularValorPedido(): float {
        return $this->quantidade * $this->produto->getValor();
    }

    public function obterProdutos(): array {
        return json_decode($this->produtosKit, true) ?: [];
    }

    public function definirProdutos(array $produtos): void {
        $this->produto->definirProdutos($produtos);
    }

    public function setProdutosKit(?string $produtosKit): void {
        $this->produtosKit = $produtosKit;
    }

    public function getProdutosKit(): ?string {
        return $this->produtosKit;
    }
}
