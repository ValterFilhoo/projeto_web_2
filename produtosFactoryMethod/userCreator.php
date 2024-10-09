<?php 

// Classe abstrata da fábrica de produtos (Creator), é a partir dela que as classes concretas responsáveis por criar um produto irá instanciar seus produtos.
abstract class UserCreator {
    
    // Atributo que irá guardar o produto que será instanciado por cada fábrica concreta (ConcreteCreator).
    private $user;

    // Método abstrato do padrão FactoryMethod, que será responsável por instanciar um produto e retornar ele instanciado.
    abstract public function factoryMethod($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado);

    // Método de operação que ajudará a criar e retornar o produto.
    public function criarUsuario($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado): mixed {

        $this->user = $this->factoryMethod($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado);

        return $this->user;

    }


}

?>