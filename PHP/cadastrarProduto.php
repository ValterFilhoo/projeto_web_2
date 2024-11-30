<?php 
  session_start();
  
  $isAuthenticated = isset($_SESSION['autenticado']) && $_SESSION['autenticado'];
  $tipoUsuario = isset($_SESSION['tipoConta']) ? $_SESSION['tipoConta'] : ''; 
  $userId = isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : '';
  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELETROWONKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/cadastrarProduto.css">
</head>
<body>
  <nav class="cabecalho">
    <div class="perfil">
      <a href="login.php"><img src="../img/perfil.png" alt="perfil" width="20px"></a>
      <a href="login.php">
      <?php 
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
      <img src="../img/lupa.png" alt="lupa" height="30px">
    </div>
    
    <div class="nav-right">
      <select class="pageSelect">
        <option value="">Fale Conosco</option>
        <option value="qsomos.php">Sobre Nós</option>
      </select>   
      <a href="carrinho.php" class="cart-icon">🛒</a>
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

  <section class="main-container">
    <div class="formulario-container">
      <h2>Cadastrar Produto</h2> 

      <form>
        <label for="imagem-produto" class="input-label">Imagem do Produto</label>
        <div class="input-imagem-container" onclick="document.getElementById('imagem-produto').click();">   
          <input type="file" id="imagem-produto" name="imagem" accept="image/*" required> 
          <div class="input-imagem-placeholder"> 
            <img id="imagem-preview" class="icone" src="../img/iconAdcionarImagem.png" alt="Adicionar imagem"> 
            <p id="imagem-mensagem">Selecione uma imagem para o produto clicando aqui.</p> 
          </div> 
        </div>

        <label for="nome-produto">Nome do produto</label> 
        <input type="text" id="nome-produto" name="nome" required> 

        <label for="valor-produto">Valor do produto</label> 
        <input type="number" id="valor-produto" name="valor" step="0.01" min="0" required> 

        <label for="quantidade-produto">Quantidade</label> 
        <input type="number" id="quantidade-produto" name="quantidade" required> 

        <label for="categoria-produto">Categoria do produto</label> 
        <select id="categoria-produto" name="categoria" onchange="atualizarTipos()" required> 
          <option value="">Selecione uma categoria</option>
          <option value="Arduino">Arduino</option> 
          <option value="Display">Display</option> 
          <option value="Motor">Motor</option>
          <option value="RaspberryPI">RaspberryPI</option> 
          <option value="Sensores">Sensores</option> 
        </select> 
        
        <label for="tipo-produto">Tipo do produto</label> 
        <select id="tipo-produto" name="tipo" onchange="atualizarFormulario()" required disabled> 
          <option value="">Selecione uma categoria primeiro</option>
        </select> 

        <label for="descricao-produto">Descrição do produto</label> 
        <textarea id="descricao-produto" name="descricao" rows="4" required></textarea> 

        <div id="campos-kit" style="display: none;">
          <label for="produtos-kit">Produtos do Kit</label>
          <div id="produtos-kit-checkboxes">
          <!-- Caixas de seleção dos produtos serão inseridas aqui --> 
          </div>
          
          <div id="produtos-kit-detalhes">
            <!-- Campos para quantidade e valor dos produtos do kit serão adicionados aqui -->
          </div>
        </div>

        <button type="submit">Cadastrar</button>
      </form>
    </div>
  </section>

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
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 Empresa | Todos os direitos reservados.</p>
    </div>
  </footer>

  <script src="../js/cadastrarProduto/cadastrarProduto.js"></script>
  <script src="../js/cadastrarProduto/enviarFormulario.js"></script>
</body>
</html>
