<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/carrinho.css">

</head>
<body>
    <header>
        <h1>Meu Carrinho</h1>
        <nav>
            <a href="#">Início</a> |
            <a href="#">Produtos</a> |
            <a href="#">Contato</a>
        </nav>
    </header>

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
            <button class="finalizar-compra">Finalizar Compra</button>
            <a href="#" class="continuar-comprando">Continuar Comprando</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Minha Loja - Todos os direitos reservados.</p>
    </footer>
    <script src="../js/carrinho/carregarCarrinho.js"></script>
</body>
</html>
