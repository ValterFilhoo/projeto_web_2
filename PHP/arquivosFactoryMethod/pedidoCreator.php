<?php

require_once __DIR__ . "/pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/pedido.php";

abstract class PedidoCreator {
    
    private Pedido $pedido; 

    abstract public function factoryMethod(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido, float $valor, ?string $chavePix = null, ?string $numeroCartao = null, int $quantidadeParcelas = null, ?string $numeroBoleto = null): Pedido;


    public function criarPedido(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido, float $valor, ?string $chavePix = null, ?string $numeroCartao = null, int $quantidadeParcelas = null, ?string $numeroBoleto = null): Pedido {
        
        return $this->pedido = $this->factoryMethod($idUsuario, $dataPedido, $tipoPagamento, $itensPedido, $valor, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto);

    }

}
