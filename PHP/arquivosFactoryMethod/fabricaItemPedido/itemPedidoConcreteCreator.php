<?php

require_once __DIR__ . "/../itemPedidoCreator.php";
require_once __DIR__ . "/../itemPedidoConcrete/itemPedidoConcrete.php"; 
require_once __DIR__ . "/../itemPedidoConcrete/itemPedidoKit.php"; 
require_once __DIR__ . "/../../composite/itemPedidoComponent.php";

class ItemPedidoConcreteCreator extends ItemPedidoCreator {

    public function retornarInstanciaItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedidoComponent {
        
        return new ItemPedidoKit($produto, $quantidade);
        
    }
    
}
