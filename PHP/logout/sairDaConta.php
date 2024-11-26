<?php

    // Iniciar a sessão
    session_start();

    // Destruir todas as variáveis de sessão
    $_SESSION = array();

    // Finalmente, destruir a sessão
    session_destroy();

    // Redirecionar para a página de login
    header("Location: ../login.php");
    exit;
