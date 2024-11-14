<?php

    class PixStrategy extends FormaPagamentoStrategy {

        private string $chavePix = null;

        public function calcularValorFinal(float $valorBasePedido): float {

            // Verificando se a chave pix foi informada.
            if ($this->getChavePix() === null) {
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

        public function setChavePix(string $chave): void {
            $this->chavePix = $chave;
        }

        public function getChavePix(): string {
            return $this->chavePix;
        }
        

    }