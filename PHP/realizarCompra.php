<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../Css/realizarCompra.css">
</head>
<body data-user-id="<?php echo htmlspecialchars($_SESSION['id']); ?>" data-api-usuario-url="../PHP/buscarUsuarios/buscarUsuarioPorId.php">
    
<nav class="cabecalho">
    <div class="perfil">
        <a id="perfil-link"><img src="../img/perfil.png" alt="perfil" width="20px"></a>
        <a id="perfil-nome">
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
        <h1 class="logo-nome">Eletrowonka</h1>
    </div>
    <div class="search-bar">
        <input type="text" id="search-bar-input" placeholder="Faça sua pesquisa">
        <img src="../img/lupa.png" alt="lupa" height="30px"> <!-- Ajuste a altura conforme necessário -->
    </div>
    
    <div class="nav-right">
        <select id="pageSelect" class="pageSelect">
            <option value="">Fale Conosco</option>
            <option value="qsomos.php">Sobre Nós</option>
        </select>   
        <a class="cart-icon">🛒</a>
    </div>
    
    <ul class="navegacao-topicos">
        <li><a href="./index.php" class="navegacao-item">Página Inicial</a></li>
        <li><a href="./categoriaArduino.php" class="navegacao-item">Arduino</a></li>
        <li><a href="./categoriaDisplay.php" class="navegacao-item">Display</a></li>
        <li><a href="./categoriaMotor.php" class="navegacao-item">Motor</a></li>
        <li><a href="./categoriaRaspberryPI.php" class="navegacao-item">RaspberryPI</a></li>
        <li><a href="./categoriaSensores.php" class="navegacao-item">Sensores</a></li>
    </ul>
</nav>

<section class="resumo-produtos">
    <h2 class="resumo-titulo">Resumo dos Produtos</h2>
    <table class="tabela-produtos">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody id="produtos-selecionados">
            <!-- Os produtos selecionados serão inseridos aqui dinamicamente -->
        </tbody>
    </table>
    <div class="resumo-total">
        <span class="total" id="total-compra">Total: R$ 0,00</span>
    </div>
</section>

<div class="dados-pagamento">
    <section class="informacoes-usuario">
        <h2 class="informacoes-titulo">Informações do Usuário</h2>
        <form id="form-usuario">

            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" readonly required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" readonly required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" readonly required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" readonly required>

            <label for="sexo">Sexo:</label>
            <input type="text" id="sexo" name="sexo" readonly required>

            <label for="dataNascimento">Data de Nascimento:</label>
            <input type="date" id="dataNascimento" name="dataNascimento" readonly required>

            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" readonly required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" readonly required>

            <label for="numeroEndereco">Número:</label>
            <input type="text" id="numeroEndereco" name="numeroEndereco" readonly required>

            <label for="complemento">Complemento:</label>
            <input type="text" id="complemento" name="complemento" readonly required>

            <label for="referencia">Referência:</label>
            <input type="text" id="referencia" name="referencia" readonly required>

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" readonly required>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" readonly required>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" readonly required>
        </form>
    </section>

    <section class="opcoes-pagamento">
        <h2 class="opcoes-titulo">Opções de Pagamento</h2>
        <form id="form-pagamento">
            <div class="forma-pagamento">
                <input type="radio" id="pix" name="pagamento" value="pix" required>
                <label for="pix">PIX (5% de desconto)</label>
                <p id="valor-pix"></p>
            </div>
            <div class="forma-pagamento">
                <input type="radio" id="cartao" name="pagamento" value="cartao_credito" required>
                <label for="cartao">Cartão de Crédito</label>
                <input type="text" id="numero-cartao" name="numero-cartao" placeholder="Número do Cartão">
                <input type="number" id="parcelas" name="parcelas" placeholder="Quantidade de Parcelas" min="1" max="6">
                <p id="valor-cartao"></p>
            </div>
            <div class="forma-pagamento">
                <input type="radio" id="boleto" name="pagamento" value="boleto" required>
                <label for="boleto">Boleto Bancário</label>
                <p id="valor-boleto"></p>
            </div>
        </form>
    </section>
</div>

<div class="botoes-acao">
    <button class="botao-voltar" id="botao-voltar">Cancelar</button>
    <button class="finalizar-compra" id="botao-finalizar">Finalizar Compra</button>
</div>

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

<div id="notificacao" class="notificacao"></div>

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


<script src="../js/finalizarCompra/finalizarCompra.js"></script>
<script src="../js/paginaInicial/modalPerfil.js"></script>
</body>
</html>
