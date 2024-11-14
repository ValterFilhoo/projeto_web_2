<?php

    abstract class FormaPagamentoStrategy {
        
        protected float $porcentagemDesconto = null;

        public abstract function calcularvalorFinal(float $valorBasePedido): float;

        public function setPorcentagemDesconto(float $porcentagemDesconto): void {
            $this->porcentagemDesconto = $porcentagemDesconto;
        }

        public function getPorcentagemDesconto(): float {
            return $this->porcentagemDesconto;
        }

    }