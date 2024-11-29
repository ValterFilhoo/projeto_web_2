<?php

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productDisplay/displayLcdConcretProduct.php"; 
require_once __DIR__ . "/../productDisplay/displayLedConcretProduct.php"; 
require_once __DIR__ . "/../productDisplay/displayOledConcretProduct.php"; 
require_once __DIR__ . "/../kitDisplayConcrete/kitDisplayConcrete.php";

// A classe concreta da Fábrica de Display, herdando o comportamento da fábrica abstrata (Creator).
class DisplayConcreteCreator extends ProdutoCreator {

    // Implementando o método do padrão de projeto para criação do produto específico dessa fábrica.
    public function retornarInstanciaProduto(
        int $id, 
        string $imagemProduto, 
        string $nomeProduto, 
        float $valorProduto, 
        int $quantidadeProduto, 
        string $categoriaProduto, 
        string $tipoProduto, 
        string $descricaoProduto, 
        array $produtosKit = []
    ): ItemPedidoComponent {

        switch ($tipoProduto) {

            case 'Kit':
                // Armazenar os produtos do kit como um array de produtos com nome e quantidade.
                return new DisplayConcreteKit($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $descricaoProduto, 
                $produtosKit);

            case 'LED':  
                // Retornando a instância do produto instanciado.
                return new DisplayLedConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'OLED':
                // Retornando a instância do produto instanciado.
                return new DisplayOledConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'LCD':
                // Retornando a instância do produto instanciado.
                return new DisplayLcdConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            default:
                throw new Exception("Erro. Tipo de Display inválido.");
        } 
    }
}
