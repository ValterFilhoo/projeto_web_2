<?php

require_once __DIR__ . "/../pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/../pedido.php";
require_once __DIR__ . "/../pedidoCreator.php";

class PedidoConcreteCreator extends PedidoCreator {

    public function retornarInstanciaPedido(
        int $idUsuario, 
        string $dataPedido, 
        string $tipoPagamento, 
        array $itensPedido, 
        float $valor, 
        ?string $chavePix, 
        ?string $numeroCartao, 
        ?int $quantidadeParcelas, 
        ?string $numeroBoleto, 
        ?float $valorParcelas
    ): Pedido {
        return new PedidoConcrete(
            $idUsuario, 
            $dataPedido, 
            $tipoPagamento, 
            $itensPedido, 
            $valor, 
            $chavePix, 
            $numeroCartao, 
            $quantidadeParcelas, 
            $numeroBoleto, 
            $valorParcelas
        );

    }

}


