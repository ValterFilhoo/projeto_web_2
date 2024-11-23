<?php

// Funções para gerar chave Pix e número de boleto automaticamente
function gerarChavePix() {
    return 'pix_' . uniqid(); // Gera uma chave Pix única
}

function gerarNumeroBoleto() {
    return 'boleto_' . uniqid(); // Gera um número de boleto único
}
