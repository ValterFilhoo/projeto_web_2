<?php

interface ItemPedidoComponent {
    public function getId(): int;
    public function getImagem(): string;
    public function getNome(): string;
    public function getValor(): float;
    public function getQuantidade(): int;
    public function getCategoria(): string;
    public function getTipo(): string;
    public function getDescricao(): string;
    public function calcularValorPedido(): float;
    public function obterProdutos(): array;
    public function definirProdutos(array $produtos): void;
    
}
