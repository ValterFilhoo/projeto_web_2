<?php

require_once __DIR__ . "/../pedido.php";

class PedidoConcrete implements Pedido {

    private int $id;
    private int $idUsuario;
    private string $dataPedido;
    private string $tipoPagamento;
    private ?string $chavePix; 
    private ?string $numeroCartao; 
    private ?int $quantidadeParcelas; 
    private ?string $numeroBoleto;
    private float $valor; 
    private ?float $valorParcelas; 
    private array $itensPedido;

    public function __construct(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido, float $valor, ?string $chavePix = null, ?string $numeroCartao = null, int $quantidadeParcelas = null, ?string $numeroBoleto = null, ?float $valorParcelas = null) { 

        $this->idUsuario = $idUsuario; 
        $this->dataPedido = $dataPedido;
        $this->tipoPagamento = $tipoPagamento;
        $this->itensPedido = $itensPedido;
        $this->chavePix = $chavePix;
        $this->numeroCartao = $numeroCartao;
        $this->quantidadeParcelas = $quantidadeParcelas; 
        $this->numeroBoleto = $numeroBoleto;
        $this->valor = $valor;
        $this->valorParcelas = $valorParcelas; 

    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setChavePix(string $chavePix): void {
        $this->chavePix = $chavePix;
    }

    public function setNumeroCartao(string $numeroCartao): void {
        $this->numeroCartao = $numeroCartao;
    }

    public function setQuantidadeParcelas(int $quantidadeParcelas): void {
        $this->quantidadeParcelas = $quantidadeParcelas;
    }

    public function setNumeroBoleto(string $numeroBoleto): void {
        $this->numeroBoleto = $numeroBoleto;
    }

    public function setValor(float $valor): void {
        $this->valor = $valor;
    }

    public function setValorParcelas(float $valorParcelas): void {
        $this->valorParcelas = $valorParcelas; 
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function getDataPedido(): string {
        return $this->dataPedido;
    }

    public function getTipoPagamento(): string {
        return $this->tipoPagamento;
    }

    public function getChavePix(): ?string { 
        return $this->chavePix;
    } 

    public function getNumeroCartao(): ?string { 
        return $this->numeroCartao; 
    }

    public function getQuantidadeParcelas(): ?int { 
        return $this->quantidadeParcelas; 
    } 

    public function getNumeroBoleto(): ?string { 
        return $this->numeroBoleto; 
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function getValorParcelas(): ?float {
        return $this->valorParcelas;
    }

    public function getItensPedido(): array {
        return $this->itensPedido;
    }

    public function adicionarItem(ItemPedidoComponent $item): void {
        $this->itensPedido[] = $item;
    }

}
