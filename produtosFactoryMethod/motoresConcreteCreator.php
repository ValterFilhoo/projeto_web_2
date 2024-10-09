<?php 

// Importando os arquivos das classes utilizadas aqui.
require '/xampp/htdocs/projeto_web_2/produtosfactoryMethod/produtoCreator.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/sensorArduinoConcreteProduct.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/placaArduinoConcreteProduct.php';

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class MotoresConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {
        
        switch ($tipoProduto) {

            case 'Bomba':  
                
                // Retornando a instância do produto instanciado.
                return new BombaMotorConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'Motor DC':

                // Retornando a instância do produto instanciado.
                return new MotorDcConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.
            
        }
       
        
    }

}

?>