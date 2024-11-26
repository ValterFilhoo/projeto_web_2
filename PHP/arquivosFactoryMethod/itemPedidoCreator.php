<?php

require_once __DIR__ . "/itemPedidoConcrete/itemPedidoConcrete.php";
require_once __DIR__ . "/../composite/itemPedidoComponent.php";

abstract class ItemPedidoCreator {

    private ItemPedido $itemPedido;

    abstract public function retornarInstanciaItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedido;

    public function criarItemPedido(ItemPedidoComponent $produto, int $quantidade): ItemPedido {
        return $this->itemPedido = $this->retornarInstanciaItemPedido($produto, $quantidade);
    }
    
}
