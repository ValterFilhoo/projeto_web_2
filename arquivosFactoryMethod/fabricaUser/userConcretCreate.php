<?php 

// Importando os arquivos das classes utilizadas aqui.
require '/xampp/htdocs/projeto_web_2/produtosfactoryMethod/produtoCreator.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/sensorArduinoConcreteProduct.php';
require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/placaArduinoConcreteProduct.php';

// A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
class UserConcreteCreator extends UserCreator {

    // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
    public function factoryMethod($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado): UserClienteConcrete {
        
    switch ($tipo) {

            case 'Cliente':  
                
                // Retornando a instância do produto instanciado.
                return new UserClienteConcrete($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado);

            // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.
            
        }
       
        
    }

}