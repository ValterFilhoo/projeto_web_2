<?php

require_once __DIR__ . "/formaPagamentoStrategy.php";

class CartaoCreditoStrategy extends FormaPagamentoStrategy {

    private ?string $numeroCartao = null;
    private ?int $quantidadeParcelas = null;
    private ?float $valorParcelas = null;

    public function calcularValorFinal(float $valorBasePedido): float {

        if ($this->getNumeroCartao() === null) {
            throw new Exception("Erro. O numero do cartão está nulo.");
        }
    
        if ($this->getPorcentagemDesconto() === null) {
            throw new Exception("Erro. O valor da porcentagem de desconto está nulo.");
        }
        
        if ($this->getQuantidadeParcelas() === null) {
            throw new Exception("Erro. A quantidade das parcelas está nula.");
        }
    
        $valorDesconto = $valorBasePedido * $this->getPorcentagemDesconto();
        $valorFinal = $valorBasePedido - $valorDesconto;
        $this->calcularValorDasParcelas($valorFinal);

        return $valorFinal;
    }

    public function calcularValorDasParcelas(float $valorFinal): void { 
        $this->valorParcelas = $valorFinal / $this->quantidadeParcelas; 
    }

    public function setNumeroCartao(string $numero): void {
        $this->numeroCartao = $numero;
    }

    public function getNumeroCartao(): string {
        return $this->numeroCartao;
    }

    public function setQuantidadeParcelas(int $quantidade): void { 
        $this->quantidadeParcelas = $quantidade; 
    } 

    public function getQuantidadeParcelas(): int { 
        return $this->quantidadeParcelas; 
    }

    public function setValorParcelas(float $valor): void {
        $this->valorParcelas = $valor;
    }

    public function getValorParcelas(): float {
        return $this->valorParcelas;
    }
    
}
