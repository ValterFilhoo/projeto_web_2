<?php 

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productDisplay/displayLcdConcretProduct.php"; 
require_once __DIR__ . "/../productDisplay/displayLedConcretProduct.php"; 
require_once __DIR__ . "/../productDisplay/displayOledConcretProduct.php"; 

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class DisplayConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod(int $id, 
    string $imagemProduto, 
    string $nomeProduto, 
    float $valorProduto, 
    int $quantidadeProduto, 
    string $categoriaProduto, 
    string $tipoProduto, 
    string $descricaoProduto
    ): ItemPedidoComponent {
        
        switch ($tipoProduto) {

            case 'LED':  
                
                // Retornando a instância do produto instanciado.
                return new DisplayLedConcreteProduct($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);

            case 'OLED':

                // Retornando a instância do produto instanciado.
                return new DisplayOledConcreteProduct($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);
    
            case 'LCD':


                // Retornando a instância do produto instanciado.
                return new DisplayLcdConcreteProduct($id, $imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto);
    
            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.

            default: // Se o tipo do produto não for igual a um dos casos acima.

                throw new Exception("Erro. Tipo de Display inválido.");
            
        }
       
        
    }

}

