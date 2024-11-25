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
            <h2>Opções de Pagamento</h2>
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

    <button class="finalizar-compra" id="botao-finalizar">Finalizar Compra</button>

    <footer>
        <p>&copy; 2024 Minha Loja - Todos os direitos reservados.</p>
    </footer>

    <script src="../js/finalizarCompra/finalizarCompra.js"></script>
</body>
</html>
