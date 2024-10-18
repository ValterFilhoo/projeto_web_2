<?php

    require_once "conexaoBDSingleton.php";
    require_once "configConexao.php";

    $conexaoInstanciada = ConexaoBDSingleton::getInstancia(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_ESCHEMA, BD_PORTA);

