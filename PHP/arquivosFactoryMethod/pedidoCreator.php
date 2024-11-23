<?php

require_once __DIR__ . "/pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/pedido.php";

abstract class PedidoCreator {
    
    private Pedido $pedido; 

    abstract public function factoryMethod(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido): Pedido;


    public function criarPedido(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido): Pedido {
        
        return $this->pedido = $this->factoryMethod($idUsuario, $dataPedido, $tipoPagamento, $itensPedido);

    }

}
