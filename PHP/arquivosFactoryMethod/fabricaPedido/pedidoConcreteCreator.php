<?php

require_once __DIR__ . "/../pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/../pedido.php";
require_once __DIR__ . "/../pedidoCreator.php";

class PedidoConcreteCreator extends PedidoCreator {
    
    public function factoryMethod(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido, float $valor, ?string $chavePix = null, ?string $numeroCartao = null, int $quantidadeParcelas = null, ?string $numeroBoleto = null): Pedido {

        return new PedidoConcrete($idUsuario, $dataPedido, $tipoPagamento, $itensPedido, $valor, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto);
         
    }

}

