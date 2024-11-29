<?php

require_once __DIR__ . "/itemPedidoConcrete/itemPedidoConcrete.php";
require_once __DIR__ . "/../composite/itemPedidoComponent.php";

abstract class ItemPedidoCreator {

    private ItemPedidoComponent $itemPedido;

    abstract public function retornarInstanciaItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedidoComponent;

    public function criarItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedidoComponent {
        return $this->itemPedido = $this->retornarInstanciaItemPedido($produto, $quantidade);
    }
    
}
