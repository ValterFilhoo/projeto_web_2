<?php

require_once __DIR__ . "/../product.php";
require_once __DIR__ . "/../itemPedido.php";

class ItemPedidoConcrete implements ItemPedido, ItemPedidoComponent {
    
    private $idPedido;
    private Product $produto;
    private int $quantidade;

    public function __construct(ItemPedidoComponent $produto, int $quantidade) {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
    }

    public function setProduto(Product $produto): void {
        $this->produto = $produto;
    }

    public function setIdPedido($idPedido): void { 
        $this->idPedido = $idPedido; 
    }

    public function getProduto(): Product {
        return $this->produto;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }

    public function getIdPedido(): int {
        return $this->idPedido;
    }
    
    public function getIdProduto(): int {
        return $this->produto->getId();
    }

    public function getNomeProduto(): string {
        return $this->produto->getNome();
    }

    public function getValor(): float {
        return $this->produto->getValor();
    }

    public function getCategoriaProduto(): string {
        return $this->produto->getCategoria();
    }

    public function getTipoProduto(): string {
        return $this->produto->getTipo();
    }

    public function getDescricaoProduto(): string {
        return $this->produto->getDescricao();
    }

    public function getImagemProduto(): string {
        return $this->produto->getImagem();
    }

    public function calcularValorPedido(): float { 
        return $this->quantidade * $this->produto->getValor(); 
    }



}
