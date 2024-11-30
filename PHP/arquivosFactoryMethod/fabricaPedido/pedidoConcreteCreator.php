<?php

require_once __DIR__ . "/../pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/../pedido.php";
require_once __DIR__ . "/../pedidoCreator.php";

class PedidoConcreteCreator extends PedidoCreator {

    public function retornarInstanciaPedido(
        int $idUsuario, 
        string $data, 
        string $tipoPag, 
        array $itens, 
        float $valor, 
        ?string $chavePix, 
        ?string $numeroCartao, 
        ?int $qtdParcelas, 
        ?string $boleto, 
        ?float $valorParcelas
    ): Pedido {
        return new PedidoConcrete(
        $idUsuario, 
        $data, 
        $tipoPag, 
        $itens, 
        $valor, 
        $chavePix, 
        $numeroCartao, 
        $qtdParcelas, 
        $boleto, 
        $valorParcelas
        );

    }

}


