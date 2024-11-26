<?php 

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productSensores/sensorAudioConcreteProduct.php"; 
require_once __DIR__ . "/../productSensores/sensorTemperaturaConcreteProduct.php"; 

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class SensoresConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function retornarInstanciaProduto(int $id, 
    string $imagemProduto, 
    string $nomeProduto, 
    float $valorProduto, 
    int $quantidadeProduto, 
    string $categoriaProduto, 
    string $tipoProduto, 
    string $descricaoProduto
    ): ItemPedidoComponent {
        
        switch ($tipoProduto) {

            case 'Sensor de áudio':  
                
                // Retornando a instância do produto instanciado.
                return new SensorAudioConcreteProduct($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'Sensor de temperatura':

                // Retornando a instância do produto instanciado.
                return new SensorTemperaturaConcreteProduct($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.

            default: // Se o tipo do produto não for igual a um dos casos acima.

                throw new Exception("Erro. Tipo de Sensores inválido.");
            
        }
       
        
    }

}

?>