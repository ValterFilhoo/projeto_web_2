<?php

interface Pedido {
    public function getId(): int;
    public function getIdUsuario(): int;
    public function getDataPedido(): string;
    public function getTipoPagamento(): string;
    public function getItensPedido(): array;
}

