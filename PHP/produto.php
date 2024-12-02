<?php 

  session_start();

  $isAuthenticated = isset($_SESSION['autenticado']) && $_SESSION['autenticado'];
  $tipoUsuario = isset($_SESSION['tipoConta']) ? $_SESSION['tipoConta'] : ''; 
  $userId = isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : ''


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ELETROWONKA</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/produto.css">
</head>
<body data-user-id="<?php echo $userId; ?>" data-autenticado="<?php echo $isAuthenticated ? 'true' : 'false'; ?>" data-tipo-usuario="<?php echo $tipoUsuario; ?>">
  
<nav class="cabecalho">
    <div class="perfil">
      <a><img src="../img/perfil.png" alt="perfil" width="20px"></a>
      <a>
      <?php 
      // Verificando se a chave nome da sessão foi iniciada (quando o usuário é autenticado é criado essa chave). Então exibe o nome do usuário.
      echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Minha conta';
      ?> 
      </a>
    </div>
</nav>
<div id="notificacao" class="notificacao"></div>
<nav>
    <div class="logo">
        <a href="./index.php"><img src="../img/eletronico.png" alt="logo" width="40px"></a>
        <h1>Eletrowonka</h1>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Faça sua pesquisa">
        <img src="../img/lupa.png" alt="lupa" height="30px"> <!-- Ajuste a altura conforme necessário -->
    </div>
    <div class="nav-right">
        <select name="" id="" class="pageSelect">
            <option value="">Fale Conosco</option>
            <option value="qsomos.php">Sobre Nós</option>
        </select>   
        <a class="cart-icon">🛒</a>
    </div>
    <ul class="navegacao-topicos">
        <li><a href="./index.php">Página Inicial</a></li>
        <li><a href="./categoriaArduino.php">Arduino</a></li>
        <li><a href="./categoriaDisplay.php">Display</a></li>
        <li><a href="./categoriaMotor.php">Motor</a></li>
        <li><a href="./categoriaRaspberryPI.php">RaspberryPI</a></li>
        <li><a href="./categoriaSensores.php">Sensores</a></li>
    </ul>
</nav>
  
<section class="banner">
    <img src="../img/Banner.jpg" alt="Banner">
    <div class="saiba-mais">
        <div class="garantia">
            <img src="../img/icone-garantia.svg" alt="garantia">
            <p>Garantia de 90 dias <br>contra defeitos de fabricação</p>
        </div>
        <div class="qualidade">
            <img src="../img/icone-polegar de qualidade.svg" alt="qualidade">
            <p>Satisfação garantida: <br>7 dias para troca ou devolução.</p>
        </div>
        <div class="cadeado">
            <img src="../img/icone-cadeado.svg" alt="cadeado">
            <p>Blog completo com especialistas<br> para tirar as suas dúvidas</p>
        </div>
        <div class="medalha">
            <img src="../img/icone-medalha.svg" alt="medalha">
            <p>Referências em Arduíno há <br>mais de 10 anos no mercado!</p>
        </div>
        <div class="coracao">
            <img src="../img/icone-coração.svg" alt="coracao">
            <p>Mais de 80.000 clientes satisfeitos!<br>Conheça e comprove.</p>
        </div>
    </div>
</section>

<section>

      <!-- Contêiner para os produtos do carrinho -->
      <div id="carrinho"></div>

      <!-- Modal do Carrinho --> 
       <div id="carrinho-modal" class="modal"> 
          <div class="modal-content"> 
            <span class="close">&times;</span> 
            <h2>Itens do Carrinho</h2> 
            <div id="carrinho-itens"></div> 
            <div class="total-carrinho">Total: R$ <span id="total-carrinho">0,00</span></div> 
            <a href="carrinho.php" id="finalizar-compra">Finalizar Compra  </a> 
          </div> 
        </div>

    </section>

    <!-- Modal do Perfil -->
    <section>
      <div id="perfil-modal" class="modal-perfil">
        <div class="modal-content-perfil">
          <span class="close-perfil">&times;</span>
          <div id="modal-content-dynamic">
          </div>
        </div>
      </div>
    </section>

<div class="container" id="product-container">
</div>

<footer>
      <div class="footer-content">
        <div class="footer-section about">
            <h3>Sobre Nós</h3>
            <p>Somos uma empresa dedicada a fornecer soluções inovadoras para melhorar o dia a dia dos nossos clientes. Comprometidos com a qualidade e excelência.</p>
        </div>
        <div class="footer-section links">
            <h3>Links Úteis</h3>
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Serviços</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </div>
   
        <div class="footer-section contact">
            <h3>Contato</h3>
            <p>Email: contato@eletrowonka.com</p>
            <p>Telefone: (71) 1234-5678</p>
            <p>Endereço: Rua Exemplo, 123, Cidade, Estado</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 ELETROWONKA | Todos os direitos reservados.</p>
    </div>
    </footer>


<script src="../js/produto/produto.js"></script>
<script src="../js/carrinho/carrinho.js"></script>
<script src="../js/paginaInicial/modalPerfil.js"></script>

</body>
</html>
