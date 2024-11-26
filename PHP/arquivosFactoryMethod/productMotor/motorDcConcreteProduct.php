<?php 

// Importando o arquivo da classe abstrata "Product", do padrão "FactoryMethod" e da interface do "Composite".
require_once __DIR__ .  "/../product.php";
require_once __DIR__ . "/../../composite/itemPedidoComponent.php";

// Classe concreta do produto de Arduino (ConcreteProduto) recebe todos os atributos e métodos da classe abstrata "Product".
class MotorDcConcreteProduct extends Product {
    
    // Contrutor da classe "SensorArduinoConcreteProduct".
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

