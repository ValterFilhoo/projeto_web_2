<?php

require_once __DIR__ . "/pedidoConcrete/pedidoConcrete.php";
require_once __DIR__ . "/pedido.php";

abstract class PedidoCreator {
    
    private Pedido $pedido; 

    abstract public function retornarInstanciaPedido(int $idUsuario, 
    string $data, 
    string $tipoPag, 
    array $itens, 
    float $valor, 
    ?string $chavePix, 
    ?string $numeroCartao, 
    ?int $qtdParcelas, 
    ?string $boleto, 
    ?float $valorParcelas): Pedido;


    public function criarPedido(int $idUsuario, 
    string $data, 
    string $tipoPag, 
    array $itens, 
    float $valor, 
    ?string $chavePix, 
    ?string $numeroCartao, 
    ?int $qtdParcelas, 
    ?string $boleto, 
    ?float $valorParcelas): Pedido {
        
        return $this->pedido = $this->retornarInstanciaPedido($idUsuario, 
        $data, 
        $tipoPag, 
        $itens, 
        $valor, 
        $chavePix, 
        $numeroCartao, 
        $qtdParcelas, 
        $boleto, 
        $valorParcelas);
    }

}
