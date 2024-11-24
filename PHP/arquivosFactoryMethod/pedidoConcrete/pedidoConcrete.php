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
    private array $itensPedido;

    public function __construct(int $idUsuario, string $dataPedido, string $tipoPagamento, array $itensPedido, float $valor, ?string $chavePix = null, ?string $numeroCartao = null, int $quantidadeParcelas = null, ?string $numeroBoleto = null) { 

        $this->idUsuario = $idUsuario; 
        $this->dataPedido = $dataPedido;
        $this->tipoPagamento = $tipoPagamento;
        $this->itensPedido = $itensPedido;
        $this->chavePix = $chavePix;
        $this->numeroCartao = $numeroCartao;
        $this->quantidadeParcelas = $quantidadeParcelas; 
        $this->numeroBoleto = $numeroBoleto;
        $this->valor = $valor;

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

    public function getChavePix(): string|null { 
        return $this->chavePix;
    } 

    public function getNumeroCartao(): string|null { 
        return $this->numeroCartao; 
    }

    public function getQuantidadeParcelas(): int|null { 
        return $this->quantidadeParcelas; 
    } 

    public function getNumeroBoleto(): string|null { 
        return $this->numeroBoleto; 
    }

    public function getItensPedido(): array {
        return $this->itensPedido;
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function adicionarItem(ItemPedido $item): void {
        $this->itensPedido[] = $item;
    }

    
}
