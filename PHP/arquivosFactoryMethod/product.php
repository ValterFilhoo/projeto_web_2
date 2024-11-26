<?php 

require_once __DIR__ . "/../composite/itemPedidoComponent.php";

// Classe abstrata em vez da interface, do padrão FactoryMethod, pois existem atributos em comum entre todos os produtos do nosso site.
abstract class Product implements ItemPedidoComponent {

    protected int $id;
    protected string $imagem;
    protected string $nome;
    protected float $valor;
    protected int $quantidade;
    protected string $categoria;
    protected string $tipo;
    protected string $descricao;

    // Construtor da classe.
    public function __construct(int $id, string $imagemProduto, string $nomeProduto, float $valorProduto, int $quantidadeProduto, string $categoriaProduto, string $tipoProduto, string $descricaoProduto) {

        $this->id = $id;
        $this->imagem = $imagemProduto;
        $this->nome = $nomeProduto;
        $this->valor = $valorProduto;
        $this->quantidade = $quantidadeProduto;
        $this->categoria = $categoriaProduto;
        $this->tipo = $tipoProduto;
        $this->descricao = $descricaoProduto;
        
    }

    public function setId(int $id) {
        $this->id = $id;
    }

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

    public function getId(): int {
        return $this->id;
    }

    public function getImagem(): string {
        return $this->imagem;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }

    public function getCategoria(): string {
        return $this->categoria;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    // Retornando o valor do produto, que é o método que a interface "ItemPedidoComponent" do Composite obriga implementar.
    public function calcularValorPedido(): float {
        return $this->getValor();
    }

}
