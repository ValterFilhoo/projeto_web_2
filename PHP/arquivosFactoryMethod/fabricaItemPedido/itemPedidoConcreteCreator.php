<?php

require_once __DIR__ . "/../itemPedidoCreator.php";
require_once __DIR__ . "/../itemPedidoConcrete/itemPedidoConcrete.php"; 
require_once __DIR__ . "/../itemPedidoConcrete/itemPedidoKit.php"; 
require_once __DIR__ . "/../../composite/itemPedidoComponent.php";

class ItemPedidoConcreteCreator extends ItemPedidoCreator {

    public function retornarInstanciaItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedidoComponent {
        if ($produto->getTipo() === 'Kit') {
            return new ItemPedidoKit($produto, $quantidade);
        } else {
            return new ItemPedidoConcrete($produto, $quantidade);
        }
    }
    
}
