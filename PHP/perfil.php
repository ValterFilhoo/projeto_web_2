<?php 

  session_start();

  
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
    <link rel="stylesheet" href="../Css/perfil.css">
    <script async src="../js/loja.js"></script>
</head>
<body>
  <nav class="cabecalho">
    <div class="perfil">
      <a href=""><img src="../img/perfil.png" alt="perfil" width="20px"></a>
      <a href="">
      <?php 
      // Verificando se a chave nome da sessão foi iniciada (quando o usuário é autenticado é criado essa chave). Então exibe o nome do usuário.
      echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Minha conta';
       ?> 
      </a>
    </div>
  </nav>
  
  <nav>
    <div class="logo">
      <a href="./index.php"> <img src="../img/eletronico.png" alt="logo" width="40px"> </a>
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
      <li><a href="#">Categorias</a></li>
      <li><a href="#">Kits Didáticos</a></li>
      <li><a href="#">Arduino</a></li>
      <li><a href="#">Robótica</a></li>
      <li><a href="#">Automação</a></li>
      <li><a href="#">Apostilas</a></li>
      <li><a href="#">Robótica nas Escolas</a></li>
    </ul>
  </nav>

  <section class="acesso">
    <div class="login">
      <div class="acesso-div">
        <img src="../img/add.png" alt="" width="30px">
        <p>Já sou cadastrado</p>
      </div>
      <div>
        <form class="formLogin" id="formularioDeLogin" method="POST">
          <label for="login-email">E-mail:</label>
          <input type="email" id="login-email" name="email" required placeholder="Digite seu e-mail">
          <label for="login-password">Senha:</label>
          <input type="password" id="login-password" name="senha" required placeholder="Digite sua senha">
          <a href="">Esqueceu a senha?</a>
          <button type="submit">Entrar</button>
        </form>
        
      </div>
    </div>
    <div class="cadastro">
      <div class="acesso-div">
        <img src="../img/register.png" alt="" width="40px">
        <p>Ainda não possuo cadastro</p>
      </div>
      <div>
        <h2>Primeiro acesso?</h2>
        <a href="cadastro.php">Cadastre-se</a>
      </div>
    </div>
  </section>
  <section class="depoimentos">
    <h1>Depoimentos de clientes</h1>
  </section>
  <div>
    <img src="../img/Banner 2.png" alt="Banner">
  </div>
</section>
<div class="contact-container">
<h2>Contato</h2>
<form >
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Digite seu email" required>
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
    <button type="submit">Enviar</button>
  </div>
</form>
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
          <p>Email: contato@empresa.com</p>
          <p>Telefone: (11) 1234-5678</p>
          <p>Endereço: Rua Exemplo, 123, Cidade, Estado</p>
      </div>
  </div>
  <div class="footer-bottom">
      <p>&copy; 2024 Empresa | Todos os direitos reservados.</p>
  </div>
  </footer>

  <script src="../js/autenticacao/autenticacao.js"></script>
</body>
</html>
</body>
</html>
