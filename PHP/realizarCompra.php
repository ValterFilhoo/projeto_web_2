<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../Css/realizarCompra.css">
</head>
<body>
    <header>
        <h1>Finalizar Compra</h1>
        <nav>
            <a href="#">Início</a> |
            <a href="#">Produtos</a> |
            <a href="#">Contato</a>
        </nav>
    </header>

    <section class="resumo-produtos">
        <h2>Resumo dos Produtos</h2>
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
            <h2>Informações do Usuário</h2>
            <form id="form-usuario">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required>
            </form>
        </section>

        <section class="opcoes-pagamento">
            <h2>Opções de Pagamento</h2>
            <form id="form-pagamento">
                <div class="forma-pagamento">
                    <input type="radio" id="pix" name="pagamento" value="pix" required>
                    <label for="pix">PIX (5% de desconto)</label>
                    <p id="valor-pix"></p>
                </div>
                <div class="forma-pagamento">
                    <input type="radio" id="cartao" name="pagamento" value="cartao" required>
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

    <button class="finalizar-compra" id="botao-finalizar">Finalizar Compra</button>

    <footer>
        <p>&copy; 2024 Minha Loja - Todos os direitos reservados.</p>
    </footer>

    <script src="../js/finalizarCompra/finalizarCompra.js"></script>
</body>
</html>
