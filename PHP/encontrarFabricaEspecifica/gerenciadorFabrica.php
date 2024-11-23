<?php

// Caminhos dos arquivos.
require_once __DIR__ . "/../arquivosFactoryMethod/produtoCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaArduino/arduinoConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaDisplay/displayConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaMotor/motoresConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaRaspberryPI/raspberryPiConcreteCreator.php";
require_once __DIR__ . "/../arquivosFactoryMethod/fabricaSensores/sensoresConcreteCreator.php";
require_once __DIR__ . "/../crudTemplateMethod/crudProduto.php";

class GerenciadorDeFabrica {
    
    private $fabricaMap = [];

    public function __construct() {
        $this->fabricaMap['Arduino'] = new ArduinoConcreteCreator;
        $this->fabricaMap['Display'] = new DisplayConcreteCreator;
        $this->fabricaMap['Motor'] = new MotoresConcreteCreator;
        $this->fabricaMap['RaspberryPI'] = new RaspberryPiConcreteCreator;
        $this->fabricaMap['Sensores'] = new SensoresConcreteCreator;
        // Adicione outras fábricas ao mapa conforme necessário
    }

    public function obterFabrica($tipo): ProdutoCreator {

        if (array_key_exists($tipo, $this->fabricaMap)) {
            return $this->fabricaMap[$tipo];
        } else {
            throw new Exception("Fábrica para o tipo de produto $tipo não encontrada.");
        }

    }

}
