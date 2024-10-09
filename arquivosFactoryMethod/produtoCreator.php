<?php 

// Classe abstrata da fábrica de produtos (Creator), é a partir dela que as classes concretas responsáveis por criar um produto irá instanciar seus produtos.
abstract class ProdutoCreator {
    
    // Atributo que irá guardar o produto que será instanciado por cada fábrica concreta (ConcreteCreator).
    private $produto;

    // Método abstrato do padrão FactoryMethod, que será responsável por instanciar um produto e retornar ele instanciado.
    abstract public function factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

    // Método de operação que ajudará a criar e retornar o produto.
    public function criarProduto($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto): mixed {

        $this->produto = $this->factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

        return $this->produto;

    }


}

?>