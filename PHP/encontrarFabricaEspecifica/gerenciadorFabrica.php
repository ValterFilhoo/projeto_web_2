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
    }

    public function obterFabrica(string $categoria): ProdutoCreator {

        if (array_key_exists($categoria, $this->fabricaMap)) {
            return $this->fabricaMap[$categoria];
        } else {
            throw new Exception("Fábrica para o tipo de produto $categoria não encontrada.");
        }

    }

}
