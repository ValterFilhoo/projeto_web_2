<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="carrinho.css">
    <script src="script.js" defer></script>
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
            <tbody>
                <tr class="cart-product">
                    <td>
                        <div class="produto-info">
                            <img src="/img/produto1.jpg" alt="Produto 1" width="60">
                            <span>Produto 1</span>
                        </div>
                    </td>
                    <td class="produto-preco">R$ 50,00</td>
                    <td>
                        <input type="number" value="1" min="1" class="input-preco">
                        <button class="remover">Remover</button>
                    </td>
                </tr>
                <tr class="cart-product">
                    <td>
                        <div class="produto-info">
                            <img src="/img/produto2.jpg" alt="Produto 2" width="60">
                            <span>Produto 2</span>
                        </div>
                    </td>
                    <td class="produto-preco">R$ 100,00</td>
                    <td>
                        <input type="number" value="1" min="1" class="input-preco">
                        <button class="remover">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="resumo-carrinho">
            <span class="total">Total: R$ 0,00</span>
            <button class="finalizar-compra">Finalizar Compra</button>
            <a href="#" class="continuar-comprando">Continuar Comprando</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Minha Loja - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
