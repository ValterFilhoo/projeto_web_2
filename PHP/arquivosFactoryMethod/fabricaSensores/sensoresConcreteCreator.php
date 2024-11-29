<?php

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productSensores/sensorAudioConcreteProduct.php"; 
require_once __DIR__ . "/../productSensores/sensorTemperaturaConcreteProduct.php"; 
require_once __DIR__ . "/../kitSensoresConcrete/sensoresConcreteKit.php";

// A classe concreta da Fábrica de Sensores, herdando o comportamento da fábrica abstrata (Creator).
class SensoresConcreteCreator extends ProdutoCreator {

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
                return new SensoresConcreteKit($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $descricaoProduto, 
                $produtosKit);

            case 'Sensor de áudio':  
                // Retornando a instância do produto instanciado.
                return new SensorAudioConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'Sensor de temperatura':
                // Retornando a instância do produto instanciado.
                return new SensorTemperaturaConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            default: 
                throw new Exception("Erro. Tipo de Sensores inválido.");
        } 
    }
}
