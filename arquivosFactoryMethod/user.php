<?php 

// Classe abstrata em vez da interface, do padrão FactoryMethod, pois existem atributos em comum entre todos os produtos do nosso site.
abstract class User {

    protected $nome;
    protected $email;
    protected $senha;
    protected $endereco;
    protected $tipo;
    protected $telefone;
    protected $cidade;
    protected $estado;

    // Construtor da classe.
    public function __construct($nome, $email, $senha, $endereco, $tipo, $telefone, $cidade, $estado) {

        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo = $tipo;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->cidade = $cidade;
        $this->estado = $estado;
        
    }


    public function setNome($nomeProduto): void {
        $this->nome = $nomeProduto;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }
    
    public function setTipo($tipoCliente): void {
        $this->tipo = $tipoCliente;
    }

    public function setTelefone($telefone): void { 
        $this->telefone = $telefone;
    }

    public function setEndereco($endereco): void {
        $this->endereco = $endereco;
    }

    public function setCidade($cidade): void {
        $this->cidade = $cidade;
    }

    public function setEstado($estado): void { 
        $this->estado = $estado;
    }
    
    public function getNome(): mixed {
        return $this->nome;
    }
    
    public function getTelefone(): mixed { 
        return $this->telefone;
    }

    public function getCidade(): mixed {
        return $this->cidade;
    }

    public function getEstado(): mixed {
        return $this->estado;
    }

    public function getTipo(): mixed {
        return $this->tipo;
    }

}

?>