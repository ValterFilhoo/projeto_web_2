<?php

interface ItemPedido {
    public function getIdProduto(): int;
    public function getNomeProduto(): string;
    public function getQuantidade(): int;
    public function getValor(): float;
}

