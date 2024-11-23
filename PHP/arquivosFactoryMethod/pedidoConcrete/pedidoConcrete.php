<?php

require_once __DIR__ . "/../pedido.php";

class PedidoConcrete implements Pedido {
    private int $id;
    private int $idUsuario;
    private string $dataPedido;
    private string $tipoPagamento;
    private string $chavePix; 
    private $numeroCartao; 
    private int $quantidadeParcelas; 
    private int $numeroBoleto;

    private $valor; 
    private array $itensPedido;

    public function __construct(int $idUsuario, string $dataPedido, string $tipoPagamento, $itensPedido, $chavePix = null, $numeroCartao = null, $quantidadeParcelas = null, $numeroBoleto = null) { 
        $this->idUsuario = $idUsuario; 
        $this->dataPedido = $dataPedido;
        $this->tipoPagamento = $tipoPagamento;
        $this->itensPedido = $itensPedido;
        $this->chavePix = $chavePix;
        $this->numeroCartao = $numeroCartao;
        $this->quantidadeParcelas = $quantidadeParcelas; 
        $this->numeroBoleto = $numeroBoleto;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setChavePix($chavePix): void {
        $this->chavePix = $chavePix;
    }

    public function setNumeroCartao($numeroCartao): void {
        $this->numeroCartao = $numeroCartao;
    }

    public function setQuantidadeParcelas($quantidadeParcelas): void {
        $this->quantidadeParcelas = $quantidadeParcelas;
    }

    public function setNumeroBoleto($numeroBoleto): void {
        $this->numeroBoleto = $numeroBoleto;
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

    public function getChavePix() { 
        return $this->chavePix;
    } 

    public function getNumeroCartao() { 
        return $this->numeroCartao; 
    }

    public function getQuantidadeParcelas() { 
        return $this->quantidadeParcelas; 
    } 

    public function getNumeroBoleto() { 
        return $this->numeroBoleto; 
    }

    public function getItensPedido(): array {
        return $this->itensPedido;
    }

    public function adicionarItem(ItemPedido $item): void {
        $this->itensPedido[] = $item;
    }

    
}
