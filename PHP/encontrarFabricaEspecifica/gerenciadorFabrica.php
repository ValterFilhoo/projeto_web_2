<?php

// Caminhos dos arquivos.
require_once __DIR__ . "/../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
require_once __DIR__ . "/../../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
require_once __DIR__ . "/../../crudTemplateMethod/crudProduto.php";

class GerenciadorDeFabrica {
    private $fabricaMap = [];

    public function __construct() {
        $this->fabricaMap['eletronico'] = new FactoryEletronico();
        $this->fabricaMap['mobiliario'] = new FactoryMobiliario();
        // Adicione outras fábricas ao mapa conforme necessário
    }

    public function obterFabrica($tipo): ProdutoFactory {
        if (array_key_exists($tipo, $this->fabricaMap)) {
            return $this->fabricaMap[$tipo];
        } else {
            throw new Exception("Fábrica para o tipo de produto $tipo não encontrada.");
        }
    }
}
