<?php

// Importando os arquivos das classes utilizadas aqui.
require_once __DIR__ . "/../produtoCreator.php";
require_once __DIR__ . "/../productMotor/bombaMotorConcreteProduct.php"; 
require_once __DIR__ . "/../productMotor/motorDcConcreteProduct.php"; 
require_once __DIR__ . "/../kitMotorConcrete/MotorConcreteKit.php";

// A classe concreta da Fábrica de Motores, herdando o comportamento da fábrica abstrata (Creator).
class MotoresConcreteCreator extends ProdutoCreator {

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
                return new MotorConcreteKit($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $descricaoProduto, 
                $produtosKit);

            case 'Bomba':  
                // Retornando a instância do produto instanciado.
                return new BombaMotorConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            case 'Motor DC':
                // Retornando a instância do produto instanciado.
                return new MotorDcConcreteProduct($id, 
                $imagemProduto, 
                $nomeProduto, 
                $valorProduto, 
                $quantidadeProduto, 
                $categoriaProduto, 
                $tipoProduto, 
                $descricaoProduto);

            default: 
                throw new Exception("Erro. Tipo de Motor inválido.");
        } 
    }
}
