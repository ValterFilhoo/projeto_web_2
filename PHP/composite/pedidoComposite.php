<?php

class PedidoComposite implements ItemPedidoComponent {

    private $itensPedido = [];
    private FormaPagamentoStrategy $pagamentoStrategy;
    private float $valorTotalPedido;

    public function adicionarItem(ItemPedidoComponent $item) {
        $this->itensPedido[] = $item;
    }

    public function removerItem(ItemPedidoComponent $item): bool {

        // Procura o item do pedido que está inserido na lista de itens do pedido.
        // Se não encontrar o item será retornado false.
        $indiceDoItem = array_search($item, $this->itensPedido);

        if ($indiceDoItem !== false) {

            // Removendo o item da lista de itens do pedido.
            unset($this->itens[$indiceDoItem]);

            // Reorganizando a lista de itens do pedido.
            $this->itensPedido = array_values($this->itensPedido);

            return true;

        }

        return false;

    }
    

    public function definirFormaPagamento(FormaPagamentoStrategy $strategy) {
        $this->pagamentoStrategy = $strategy;
    }


    public function calcularValorPedido(): float {
        
        $valorBasePedido = 0;

        // Itera na lista de itens do pedido para calcular recursivamente os valores de cada item contidos na lista.
        foreach ($this->itensPedido as $item) {

            // Cada item que está na lista de itens do pedido, executará esse mesmo método e retornar seu preço individual, sendo assim somado cada um deles.
            $valorBasePedido += $item->calcularValorPedido();

        }

        // Verifica se a estratégia de pagamento é nula ou foi devidamente passada para o pedido.
        if ($this->pagamentoStrategy !== null) {

            // Descontando no valor total do pedido o valor do pagamento conforme a forma de pagamento escolhida.
            $this->valorTotalPedido = $this->pagamentoStrategy->calcularvalorFinal($valorBasePedido);

        } else {

            throw new Exception("Erro. A estratégia de pagamento está nula.");
            
        }

        return $this->getValorTotalPedido();

    }


    public function getValorTotalPedido(): float {
        return $this->valorTotalPedido;
    }


    
}
