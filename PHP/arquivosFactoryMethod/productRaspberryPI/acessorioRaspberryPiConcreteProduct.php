<?php 

// Importando o arquivo da classe abstrata "Product", do padrão "FactoryMethod" e da interface do "Composite".
require_once __DIR__ .  "/../product.php";
require_once __DIR__ . "/../../composite/itemPedidoComponent.php";


class AcessorioRaspberryPiConcretePoduct extends Product {
    
    // Contrutor da classe "PlacaArduinoConcreteProduct".
    public function __construct(int $id, 
    string $imagemProduto, 
    string $nomeProduto, 
    float $valorProduto, 
    int $quantidadeProduto, 
    string $categoriaProduto, 
    string $tipoProduto, 
    string $descricaoProduto
    ) {
        
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

    public function obterProdutos(): array {
        throw new Exception("Método inválido. Apenas os produtos de tipo kit pode utilizá-los.");
    }

    public function definirProdutos(array $produtos): void {
        throw new Exception("Método inválido. Apenas os produtos de tipo kit pode utilizá-los.");
    }

}

