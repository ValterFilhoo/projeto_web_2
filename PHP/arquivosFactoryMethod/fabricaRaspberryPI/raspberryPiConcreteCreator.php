<?php

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productRaspberryPI/placaRaspberryPiConcretProduct.php"; 
require_once __DIR__ . "/../productRaspberryPI/acessorioRaspberryPiConcreteProduct.php"; 
require_once __DIR__ . "/../kitRaspberryPIConcrete/raspberryPIConcreteKit.php";

// A classe concreta da Fábrica de RaspberryPi, herdando o comportamento da fábrica abstrata (Creator).
class RaspberryPiConcreteCreator extends ProdutoCreator {

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
                return new RaspberryPiConcreteKit($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $descricaoProduto, 
                $produtosKit);

            case 'Placa para RaspberryPi':  
                // Retornando a instância do produto instanciado.
                return new PlacaRaspberryPiConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'Acessório para RaspberryPi':
                // Retornando a instância do produto instanciado.
                return new AcessorioRaspberryPiConcretePoduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            default: 
                throw new Exception("Erro. Tipo de RaspberryPi inválido.");
        } 
    }
}
