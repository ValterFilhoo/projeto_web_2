<?php 

// Importando o arquivo da classe abstrata "Product", do padrão "FactoryMethod".
require 'product.php';

// Classe concreta do produto de Arduino (ConcreteProduto) recebe todos os atributos e métodos da classe abstrata "Product".
class SensorTemperaturaConcreteProduct extends Product {
    
    // Contrutor da classe "SensorArduinoConcreteProduct".
    public function __construct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {
        
        // Instanciando o construtor da classe pai (classe Product), pois os atributos gerais do produto estão nela.
        parent::__construct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

    }

    // Implementação do método abstrato, mas só utilizando uma string como teste apenas.
    public function instanciarProduto(): void {

        echo "Instanciando o produto: " . $this->nome;

    }

}

?>