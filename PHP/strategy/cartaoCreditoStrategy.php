<?php

    class CartaoCreditoStrategy extends FormaPagamentoStrategy {

        private string $numeroCartao = null;
        private int $quantidadeParcelas = null;

        public function calcularValorFinal(float $valorBasePedido): float {

            // Verificando se o número do cartão foi informado.
            if ($this->getNumeroCartao() === null) {
                throw new Exception("Erro. O valor da chave pix está nulo.");
            }
        
            // Verificando se a porcentagem de desconto em pagamento em cartão foi informada.
            if ($this->getPorcentagemDesconto() === null) {
                throw new Exception("Erro. O valor da porcentagem de desconto está nulo.");
            }
            
            if ($this->getQuantidadeParcelas() === null) {
                throw new Exception("Erro. A quantidade das parcelas está nulo.");
            }
        
            // Calculando o valor do desconto de acordo com a porcentagem informada e o valor base do pedido.
            $valorDesconto = $valorBasePedido * $this->getPorcentagemDesconto();
        
            // Valor final do pedido é o valor base menos o valor descontado de acordo com a porcentagem.
            $valorFinal = $valorBasePedido - $valorDesconto;
        
            return $valorFinal;
            
        }

        public function calcularValorDasParcelas(float $valorFinal): float { 
            
            // Calcula e retorna o valor de cada parcela dividindo o valor final pelo número de parcelas.
            return $valorFinal / $this->quantidadeParcelas; 
        
        }

        public function setNumeroCartao(string $numero):void {
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
        

    }