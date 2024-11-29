<?php

require_once __DIR__ . "/../Product.php";

class RaspberryPIConcreteKit extends Product {

    private array $produtos;

    public function __construct(
        int $id, 
        string $imagemProduto, 
        string $nomeProduto, 
        float $valorProduto, 
        int $quantidadeProduto, 
        string $categoriaProduto, 
        string $descricaoProduto, 
        array $produtos = []
    ) {
        parent::__construct($id, 
        $imagemProduto, 
        $nomeProduto, 
        $valorProduto, 
        $quantidadeProduto, 
        $categoriaProduto, 
        'Kit', 
        $descricaoProduto);
        $this->produtos = $produtos;
    }

    public function obterProdutos(): array {
        return $this->produtos;
    }

    public function definirProdutos(array $produtos): void {
        $this->produtos = $produtos;
    }

    public function calcularValorTotal(): float {

        $valorTotal = 0;
        foreach ($this->produtos as $produto) {
            $valorTotal += $produto->calcularValorPedido() * $produto->getQuantidade();
        }
        return $valorTotal;
        
    }

}
