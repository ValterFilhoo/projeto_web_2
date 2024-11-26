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
    <title>Cadastrar - Eletrowonka</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/cadastro.css">
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
  <nav class="cabecalho">
    <div class="perfil">
      <a href="login.php"><img src="../img/perfil.png" alt="perfil" width="20px"></a>
      <a href="perfil.php">Minha conta</a>
    </div>
  </nav>
  
  <div id="notificacao" class="notificacao"></div>

  <nav>
    <div class="logo">
      <img src="../img/eletronico.png" alt="logo" width="40px">
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
    <div class="container">
        <h1>Cadastrar-se</h1>
        <form action="./processarFormularios/cadastrarUsuario.php" id="formularioDeCadastro" method="post">
            <div class="grid-container">
                <div class="grid-item">
                    <label for="nome">Nome completo:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="grid-item">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>

                <div class="grid-item">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" required>
                </div>

                <div class="grid-item">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="">Selecione</option>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>

                <div class="grid-item">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="grid-item">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="grid-item">
                    <label for="data-nascimento">Data de nascimento:</label>
                    <input type="date" id="data-nascimento" name="data-nascimento" required>
                </div>
            </div>

            <h2>Endereço principal</h2>
            <div class="grid-container">
                <div class="grid-item">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" required>
                </div>

                <div class="grid-item">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>

                <div class="grid-item">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" required>
                </div>

                <div class="grid-item">
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento">
                </div>

                <div class="grid-item">
                    <label for="referencia">Referência:</label>
                    <input type="text" id="referencia" name="referencia">
                </div>

                <div class="grid-item">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" required>
                </div>

                <div class="grid-item">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" required>
                </div>

                <div class="grid-item">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" maxlength="2" required>
                </div>
            </div>

            <div class="termos">
                <input type="checkbox" id="termos" name="termos" required>
                <label for="termos">Li e concordo com os termos da política de privacidade.</label>
            </div>

            <button type="submit">Criar conta</button>
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

    <script src="../js/cadastro/cadastrarUsuario.js"></script>
</body>
</html>
