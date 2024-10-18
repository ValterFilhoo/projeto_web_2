<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELETROWONKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./Css/style.css">
    <script async src="../js/loja.js"></script>
</head>
<body>
  <nav class="cabecalho">
    <div class="perfil">
      <a href="perfil.php"><img src="./img/perfil.png" alt="perfil" width="20px"></a>
      <a href="perfil.php">Minha conta</a>
    </div>
  </nav>
  <nav>
    <div class="logo">
      <img src="./img/eletronico.png" alt="logo" width="40px">
      <h1>Eletrowonka</h1>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Faça sua pesquisa">
      <img src="./img/lupa.png" alt="lupa" height="30px"> <!-- Ajuste a altura conforme necessário -->
    </div>
    
    <div class="nav-right">
      <select name="" id="" class="pageSelect">
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
  
    <section class="banner">
      <img src="./img/Banner.jpg" alt="Banner">
      <div class="saiba-mais">
        <div class="garantia">
          <img src="./img/icone-garantia.svg" alt="garantia">
          <p>Garantia de 90 dias <br>contra defeitos de fabricação</p>
        </div>
        <div class="qualidade">
          <img src="./img/icone-polegar de qualidade.svg" alt="qualidade">
          <p>Satisfação garantida: <br>7 dias para troca ou devolução.</p>
        </div>
        <div class="cadeado">
          <img src="./img/icone-cadeado.svg" alt="cadeado">
          <p>Blog completo com especialistas<br> para tirar as suas dúvidas</p>
        </div>
        <div class="medalha">
          <img src="./img/icone-medalha.svg" alt="medalha">
          <p>referências em Arduíno há <br>mais de 10 anos no mercado!</p>
        </div>
        <div class="coracao">
          <img src="./img/icone-coração.svg" alt="coracao">
          <p>Mais de 80.000 clientes satisfeitos!<br>Conheça e comprove.</p>
        </div>
      </div>
    </section>
    <section class="produtos">
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2,000</p>
          <p>até 12x de R$166</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2,000</p>
          <p>até 12x de R$166</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2,000</p>
          <p>até 12x de R$166</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2,000</p>
          <p>até 12x de R$166</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
    </section>
    <div class="produtos">
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2,000</p>
          <p>até 12x de R$166</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2.000</p>
          <p>até 12x de R$180</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2.000</p>
          <p>até 12x de R$180</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
      <div class="notebook">
        <img src="./img/notebook.svg" alt="notebook">
          <h1>Notebook Acer</h1>
          <p>R$ 2.000</p>
          <p>até 12x de R$180</p>
          <a href="https://encurtador.com.br/PlaZq">Comprar agora</a>
      </div>
    </div>
    <section class="depoimentos">
      <h1>Depoimentos de clientes</h1>
    </section>
    <div>
      <img src="./img/Banner 2.png" alt="Banner">
    </div>
</section>
<div class="contact-container">
  <h2>Contato</h2>
  <form action="#" method="post">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Digite seu email" required>

      <label for="password">Senha:</label>
      <input type="password" id="password" name="password" placeholder="Digite sua senha" required>

      <button type="submit">Enviar</button>
  </form>
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
            <p>Email: contato@empresa.com</p>
            <p>Telefone: (11) 1234-5678</p>
            <p>Endereço: Rua Exemplo, 123, Cidade, Estado</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Empresa | Todos os direitos reservados.</p>
    </div>
    </footer>
</body>
</html>
