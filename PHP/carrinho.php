<?php 

    session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/carrinho.css">

</head>
<body data-user-id="<?php echo htmlspecialchars($_SESSION['id']); ?>">
<nav class="cabecalho">
    <div class="perfil">
      <a href="login.php"><img src="../img/perfil.png" alt="perfil" width="20px"></a>
      <a href="login.php">
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
      <li><a href="./categoriaArduino.php">Arduino</a></li>
      <li><a href="./categoriaDisplay.php">Display</a></li>
      <li><a href="./categoriaMotor.php">Motor</a></li>
      <li><a href="./categoriaRaspberryPI.php">RaspberryPI</a></li>
      <li><a href="./categoriaSensores.php">Sensores</a></li>
    </ul>
  </nav>

    <section class="carrinho">
        <h2>Itens no Carrinho</h2>

        <table class="tabela-carrinho">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody id="carrinho-produtos">
                <!-- Os produtos do carrinho serão inseridos aqui dinamicamente -->
            </tbody>
        </table>

        <div class="resumo-carrinho">
            <span class="total" id="total-carrinho">Total: R$ 0,00</span>
           <a href="realizarCompra.php"> <button class="finalizar-compra">Finalizar Compra</button> </a> 
            <a href="#" class="continuar-comprando">Continuar Comprando</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Minha Loja - Todos os direitos reservados.</p>
    </footer>
    <script src="../js/carrinho/carregarCarrinho.js"></script>
</body>
</html>
