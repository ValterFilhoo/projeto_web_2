<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/detalhesPedido.css">
</head>
<body data-api-detalhes-pedido-url="../PHP/pedidos/buscarPedidoId.php">
    <nav class="cabecalho">
        <div class="perfil">
            <a href="login.php"><img src="../img/perfil.png" alt="perfil" width="20px"></a>
            <a href="login.php">
            <?php 
            session_start();
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
            <select name="" id="" class="pageSelect">
                <option value="">Fale Conosco</option>
                <option value="qsomos.php">Sobre Nós</option>
            </select>   
            <a class="cart-icon">🛒</a>
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
                <p>referências em Arduíno há <br>mais de 10 anos no mercado!</p>
            </div>
            <div class="coracao">
                <img src="../img/icone-coração.svg" alt="coracao">
                <p>Mais de 80.000 clientes satisfeitos!<br>Conheça e comprove.</p>
            </div>
        </div>
    </section>

    <section id="detalhes-pedido-section">
        <div id="detalhes-pedido">
            <h2>Detalhes do Pedido</h2>
            <table id="detalhes-tabela">
                <tr>
                    <td>Data do Pedido:</td>
                    <td id="data-pedido"></td>
                </tr>
                <tr>
                    <td>Tipo de Pagamento:</td>
                    <td id="tipo-pagamento"></td>
                </tr>
                <tr>
                    <td>Valor:</td>
                    <td id="valor"></td>
                </tr>
            </table>
            <h3>Itens do Pedido</h3>
            <ul id="itens-pedido"></ul>
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

    <script src="../js/pedidos/detalhesPedido.js"></script>
</body>
</html>
