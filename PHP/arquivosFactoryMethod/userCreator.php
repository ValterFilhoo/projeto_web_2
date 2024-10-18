<?php 

// Classe abstrata da fábrica de produtos (Creator), é a partir dela que as classes concretas responsáveis por criar um produto irá instanciar seus produtos.
abstract class UserCreator {
    
    // Atributo que irá guardar o produto que será instanciado por cada fábrica concreta (ConcreteCreator).
    private $user;

    // Método abstrato do padrão FactoryMethod, que será responsável por instanciar um produto e retornar ele instanciado.
    abstract public function factoryMethod(string $nomeCompleto, string $email, string $cpf, string $celular, string $sexo, string $senha, string $dataNascimento, string $cep, string $endereco, int $numeroEndereco, string $complemento, string $referencia, string $bairro, $cidade, string $estado, string $tipoConta);

    // Método de operação que ajudará a criar e retornar o produto.
    public function criarUsuario(string $nomeCompleto, string $email, string $cpf, string $celular, string $sexo, string $senha, string $dataNascimento, string $cep, string $endereco, int $numeroEndereco, string $complemento, string $referencia, string $bairro, $cidade, string $estado, string $tipoConta): mixed {

        $this->user = $this->factoryMethod($nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado, $tipoConta);

        return $this->user;

    }


}

?>