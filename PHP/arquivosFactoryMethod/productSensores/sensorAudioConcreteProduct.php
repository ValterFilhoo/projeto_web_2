<?php 

// Importando o arquivo da classe abstrata "Product", do padrão "FactoryMethod" e da interface do "Composite".
require_once __DIR__ .  "/../product.php";
require_once __DIR__ . "/../../composite/itemPedidoComponent.php";


class SensorAudioConcreteProduct extends Product {
    
    public function __construct(int $id, 
    string $imagemProduto, 
    string $nomeProduto, 
    float $valorProduto, 
    int $quantidadeProduto, 
    string $categoriaProduto, 
    string $tipoProduto, 
    string $descricaoProduto) {
        
        // Instanciando o construtor da classe pai (classe Product), pois os atributos gerais do produto estão nela.
        parent::__construct($id, 
        $imagemProduto, 
        $nomeProduto, 
        $valorProduto, 
        $quantidadeProduto, 
        $categoriaProduto, 
        $tipoProduto, 
        $descricaoProduto);

    }

}
