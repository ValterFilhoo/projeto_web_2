<?php

    class BoletoStrategy extends FormaPagamentoStrategy {

        private string $numeroBoleto = null;

        public function calcularValorFinal(float $valorBasePedido): float {

            // Verificando se a chave pix foi informada.
            if ($this->getNumeroBoleto() === null) {
                throw new Exception("Erro. O valor da chave pix está nulo.");
            }
        
            // Verificando se a porcentagem de desconto no pix foi informada.
            if ($this->getPorcentagemDesconto() === null) {
                throw new Exception("Erro. O valor da porcentagem de desconto está nulo.");
            }
        
            // Calculando o valor do desconto de acordo com a porcentagem informada e o valor base do pedido.
            $valorDesconto = $valorBasePedido * $this->porcentagemDesconto;
        
            // Valor final do pedido é o valor base menos o valor descontado de acordo com a porcentagem.
            $valorFinal = $valorBasePedido - $valorDesconto;
        
            return $valorFinal;
            
        }

        public function setNumeroBoleto(string $numero):void {
            $this->numeroBoleto = $numero;
        }

        public function getNumeroBoleto(): string {
            return $this->numeroBoleto;
        }
        

    }