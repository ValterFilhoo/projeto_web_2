<?php

require_once __DIR__ . "/pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/pedido.php";

abstract class PedidoCreator {
    
    private Pedido $pedido; 

    abstract public function retornarInstanciaPedido(int $idUsuario, 
    string $dataPedido, 
    string $tipoPagamento, 
    array $itensPedido, 
    float $valor, 
    ?string $chavePix, 
    ?string $numeroCartao, 
    ?int $quantidadeParcelas, 
    ?string $numeroBoleto, 
    ?float $valorParcelas): Pedido;


    public function criarPedido(int $idUsuario, 
    string $dataPedido, 
    string $tipoPagamento, 
    array $itensPedido, 
    float $valor, 
    ?string $chavePix, 
    ?string $numeroCartao, 
    ?int $quantidadeParcelas, 
    ?string $numeroBoleto, 
    ?float $valorParcelas): Pedido {
        
        return $this->pedido = $this->retornarInstanciaPedido($idUsuario, 
        $dataPedido, 
        $tipoPagamento, 
        $itensPedido, 
        $valor, 
        $chavePix, 
        $numeroCartao, 
        $quantidadeParcelas, 
        $numeroBoleto, 
        $valorParcelas);
    }

}
