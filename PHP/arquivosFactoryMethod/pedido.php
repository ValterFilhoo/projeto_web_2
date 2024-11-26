<?php

interface Pedido {

    public function setId(int $id): void;

    public function setChavePix(string $chavePix): void;

    public function setNumeroCartao(string $numeroCartao): void;

    public function setQuantidadeParcelas(int $quantidadeParcelas): void;
    public function setNumeroBoleto(string $numeroBoleto): void;

    public function setValor(float $valor): void;

    public function setValorParcelas(float $valorParcelas): void;

    public function getId(): int;

    public function getIdUsuario(): int;

    public function getDataPedido(): string;

    public function getTipoPagamento(): string;

    public function getChavePix(): ?string;

    public function getNumeroCartao(): ?string;
    public function getQuantidadeParcelas(): ?int;

    public function getNumeroBoleto(): ?string;

    public function getValor(): float;

    public function getValorParcelas(): ?float;

    public function getItensPedido(): array;

    public function adicionarItem(ItemPedido $item): void;

}
