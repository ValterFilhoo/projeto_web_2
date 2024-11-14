<?php 

// Importando os arquivos das classes utilizadas aqui.
require_once "../produtoCreator.php";

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class ArduinoConcreteCreator extends ProdutoCreator {


    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto): Product {
        
        switch ($tipoProduto) {

            case 'Sensor':  
                
                // Retornando a instância do produto instanciado.
                return new SensorArduinoConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'Placa':

                // Retornando a instância do produto instanciado.
                return new PlacaArduinoConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.


            default: // Se o tipo do produto não for igual a um dos casos acima.

                throw new Exception("Erro. Tipo de Arduino inválido.");

        }
       
        
    }

}

?>