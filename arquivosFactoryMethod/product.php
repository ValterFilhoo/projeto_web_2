<?php 

// Classe abstrata em vez da interface, do padrão FactoryMethod, pois existem atributos em comum entre todos os produtos do nosso site.
abstract class Product {

    protected $imagem;
    protected $nome;
    protected $valor;
    protected $quantidade;
    protected $categoria;
    protected $tipo;
    protected $descricao;

    // Construtor da classe.
    public function __construct($imagemProduto, $nomeProduto, $valorProduto, $quantidadeProduto, $categoriaProduto, $tipoProduto, $descricaoProduto) {

        $this->imagem = $imagemProduto;
        $this->nome = $nomeProduto;
        $this->valor = $valorProduto;
        $this->quantidade = $quantidadeProduto;
        $this->categoria = $categoriaProduto;
        $this->tipo = $tipoProduto;
        $this->descricao = $descricaoProduto;
        
    }

    // Método abstrato que irá iniciar a instanciação do produto.
    public abstract function instanciarProduto();

    public function setImagem($imagemProduto): void {
        $this->imagem = $imagemProduto;
    }

    public function setNome($nomeProduto): void {
        $this->nome = $nomeProduto;
    }

    public function setValor($valorProduto): void {
        $this->valor = $valorProduto;
    }

    public function setQuantidade($quantidadeProduto): void {
        $this->valor = $quantidadeProduto;
    }

    public function setCategoria($categoriaProduto): void {
        $this->categoria = $categoriaProduto;
    }
    
    public function setTipo($tipoProduto): void {
        $this->tipo = $tipoProduto;
    }

    public function setDescricao($descricaoProduto): void {
        $this->descricao = $descricaoProduto;
    }

    public function getImagem(): mixed {
        return $this->imagem;
    }

    public function getNome(): mixed {
        return $this->nome;
    }

    public function getValor(): mixed {
        return $this->valor;
    }

    public function getQuantidade(): mixed {
        return $this->quantidade;
    }

    public function getCategoria(): mixed {
        return $this->categoria;
    }

    public function getTipo(): mixed {
        return $this->tipo;
    }

    public function getDescricao(): mixed {
        return $this->descricao;
    }

}

?>