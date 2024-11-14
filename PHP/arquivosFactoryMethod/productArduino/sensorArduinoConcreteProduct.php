<?php 

// Importando o arquivo da classe abstrata "Product", do padrão "FactoryMethod" e da interface do "Composite".
require_once "../product.php";
require_once "../../composite/itemPedidoComponent.php";

// Classe concreta do produto de Arduino (ConcreteProduto) recebe todos os atributos e métodos da classe abstrata "Product".
class SensorArduinoConcreteProduct extends Product implements ItemPedidoComponent{
    
    // Contrutor da classe "SensorArduinoConcreteProduct".
    public function __construct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {
        
        // Instanciando o construtor da classe pai (classe Product), pois os atributos gerais do produto estão nela.
        parent::__construct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

    }

    public function calcularValorPedido(): float {
        return $this->getValor();
    }

}

