<?php 

require_once __DIR__ .  "/./product.php";

// Classe abstrata da fábrica de produtos (Creator), é a partir dela que as classes concretas responsáveis por criar um produto irá instanciar seus produtos.
abstract class ProdutoCreator {
    
    // Atributo que irá guardar o produto que será instanciado por cada fábrica concreta (ConcreteCreator).
    private ItemPedidoComponent $produto;

    // Método abstrato do padrão FactoryMethod, que será responsável por instanciar um produto e retornar ele instanciado.
    abstract public function retornarInstanciaProduto(int $id, string $imagemProduto, string $nomeProduto, float $valorProduto, int $quantidadeProduto, string $categoriaProduto, string $tipoProduto, string $descricaoProduto): ItemPedidoComponent;

    // Método de operação que ajudará a criar e retornar o produto.
    public function criarProduto(int $id, string $imagemProduto, string $nomeProduto, float $valorProduto, int $quantidadeProduto, string $categoriaProduto, string $tipoProduto, string $descricaoProduto): ItemPedidoComponent {

        $this->produto = $this->retornarInstanciaProduto($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

        return $this->produto;

    }


}

