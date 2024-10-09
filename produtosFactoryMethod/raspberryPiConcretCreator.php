<?php 

// Importando os arquivos das classes utilizadas aqui.
require '/xampp/htdocs/projeto_web_2/produtosfactoryMethod/produtoCreator.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/sensorArduinoConcreteProduct.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/placaArduinoConcreteProduct.php';

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class RaspberryPiConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {
        
        switch ($tipoProduto) {

            case 'Placa para RaspberryPi':  
                
                // Retornando a instância do produto instanciado.
                return new PlacaRaspberryPiConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'Acessórios para RaspberryPi':

                // Retornando a instância do produto instanciado.
                return new AcessoriosRaspberryPiConcretPoduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.
            
        }
       
        
    }

}