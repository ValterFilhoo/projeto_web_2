<?php

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productArduino/placaArduinoConcreteProduct.php"; 
require_once __DIR__ . "/../productArduino/AcessorioArduinoConcreteProduct.php"; 
require_once __DIR__ . "/../kitArduinoConcrete/ArduinoConcreteKit.php";

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class ArduinoConcreteCreator extends ProdutoCreator {

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
                return new ArduinoConcreteKit($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $descricaoProduto, 
                $produtosKit);

            case 'Acessório para Arduino':
                // Retornando a instância do produto instanciado.
                return new AcessorioArduinoConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'Placa':
                // Retornando a instância do produto instanciado.
                return new PlacaArduinoConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            default:
                throw new Exception("Erro. Tipo de Arduino inválido.");
        } 

    }

}
