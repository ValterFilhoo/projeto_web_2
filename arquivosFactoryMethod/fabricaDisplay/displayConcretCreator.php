<?php 

// Importando os arquivos das classes utilizadas aqui.
require '/xampp/htdocs/projeto_web_2/produtosfactoryMethod/produtoCreator.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/displayLcdConcreteProduct.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/displayOledConcreteProduct.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/displayOledConcreteProduct.php';

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class DisplayConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {
        
        switch ($tipoProduto) {

            case 'LED':  
                
                // Retornando a instância do produto instanciado.
                return new DisplayLedConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'OLED':

                // Retornando a instância do produto instanciado.
                return new DisplayOledConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);
    
            case 'LCD':


                // Retornando a instância do produto instanciado.
                return new DisplayLcdConcreteProduct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);
    
            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.
            
        }
       
        
    }

}

?>