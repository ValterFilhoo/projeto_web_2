document.addEventListener('DOMContentLoaded', function() {
    function getUserId() {
        // Esta função deve ser substituída pela lógica real para obter o ID do usuário autenticado
        return 'usuario123'; // Exemplo de ID de usuário
    }

    const userId = getUserId();
    carregarProdutosSelecionados(userId);
    carregarDadosUsuario(userId);

    // Adiciona eventos aos métodos de pagamento para recalcular o valor total e as parcelas
    document.querySelectorAll('input[name="pagamento"]').forEach(input => {
        input.addEventListener('change', atualizarValorTotal);
    });

    document.getElementById('parcelas').addEventListener('input', atualizarValorTotal);

    // Adiciona evento ao botão de finalizar compra
    document.getElementById('botao-finalizar').addEventListener('click', finalizarCompra);
});

function carregarProdutosSelecionados(userId) {
    const chaveCarrinho = `carrinho_${userId}`;
    let carrinho = localStorage.getItem(chaveCarrinho);
    const produtosSelecionados = document.getElementById('produtos-selecionados');
    const totalCompra = document.getElementById('total-compra');

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        produtosSelecionados.innerHTML = ''; // Limpa o contêiner antes de adicionar os produtos

        let total = 0;

        carrinho.forEach(produto => {
            const produtoTr = document.createElement('tr');
            produtoTr.innerHTML = `
                <td>
                    <div class="produto-info">
                        <img src="../${produto.imagem}" alt="${produto.nome}" width="60">
                        <span>${produto.nome}</span>
                    </div>
                </td>
                <td>R$ ${produto.valor.toFixed(2)}</td>
                <td>${produto.quantidade}</td>
                <td>R$ ${(produto.valor * produto.quantidade).toFixed(2)}</td>
            `;
            produtosSelecionados.appendChild(produtoTr);

            total += produto.valor * produto.quantidade;
        });

        totalCompra.dataset.valorBase = total; // Armazena o valor base da compra
        totalCompra.textContent = `Total: R$ ${total.toFixed(2)}`;
    } else {
        produtosSelecionados.innerHTML = '<tr><td colspan="4">Nenhum produto selecionado.</td></tr>';
        totalCompra.textContent = 'Total: R$ 0,00';
    }
}

function carregarDadosUsuario(userId) {
    // Exemplo de dados do usuário
    const usuario = {
        nome: 'João Silva',
        cpf: '123.456.789-10',
        email: 'joao.silva@email.com',
        telefone: '(11) 91234-5678'
    };

    // Preenche os campos do formulário com os dados do usuário
    document.getElementById('nome').value = usuario.nome;
    document.getElementById('cpf').value = usuario.cpf;
    document.getElementById('email').value = usuario.email;
    document.getElementById('telefone').value = usuario.telefone;
}

function atualizarValorTotal() {
    const metodoPagamento = document.querySelector('input[name="pagamento"]:checked').value;
    const valorBase = parseFloat(document.getElementById('total-compra').dataset.valorBase);
    let valorFinal;

    switch (metodoPagamento) {
        case 'pix':
            valorFinal = calcularValorFinalPix(valorBase);
            document.getElementById('valor-pix').textContent = `Valor com desconto: R$ ${valorFinal.toFixed(2)}`;
            document.getElementById('valor-cartao').textContent = '';
            document.getElementById('valor-boleto').textContent = '';
            break;
        case 'cartao':
            const parcelas = parseInt(document.getElementById('parcelas').value) || 1;
            valorFinal = calcularValorFinalCartao(valorBase, parcelas);
            const valorParcelas = (valorFinal / parcelas).toFixed(2);
            document.getElementById('valor-cartao').textContent = `Total: R$ ${valorFinal.toFixed(2)} (${parcelas}x de R$ ${valorParcelas})`;
            document.getElementById('valor-pix').textContent = '';
            document.getElementById('valor-boleto').textContent = '';
            break;
        case 'boleto':
            valorFinal = calcularValorFinalBoleto(valorBase);
            document.getElementById('valor-boleto').textContent = `Valor: R$ ${valorFinal.toFixed(2)}`;
            document.getElementById('valor-pix').textContent = '';
            document.getElementById('valor-cartao').textContent = '';
            break;
        default:
            valorFinal = valorBase;
            document.getElementById('valor-pix').textContent = '';
            document.getElementById('valor-cartao').textContent = '';
            document.getElementById('valor-boleto').textContent = '';
            break;
    }

    document.getElementById('total-compra').textContent = `Total: R$ ${valorFinal.toFixed(2)}`;
}

function finalizarCompra() {
    const nome = document.getElementById('nome').value;
    const cpf = document.getElementById('cpf').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('telefone').value;
    const metodoPagamento = document.querySelector('input[name="pagamento"]:checked').value;
    const numeroCartao = document.getElementById('numero-cartao').value;
    const parcelas = parseInt(document.getElementById('parcelas').value) || 1;

    // Validação básica dos dados
    if (!nome || !cpf || !email || !telefone || !metodoPagamento) {
        alert('Por favor, preencha todos os campos e selecione uma opção de pagamento.');
        return;
    }

    // Processar pagamento conforme a estratégia escolhida
    let valorFinal;
    switch (metodoPagamento) {
        case 'pix':
            valorFinal = calcularValorFinalPix(parseFloat(document.getElementById('total-compra').dataset.valorBase));
            break;
        case 'cartao':
            if (!numeroCartao || parcelas < 1 || parcelas > 6) {
                alert('Por favor, informe o número do cartão e uma quantidade de parcelas entre 1 e 6.');
                return;
            }
            valorFinal = calcularValorFinalCartao(parseFloat(document.getElementById('total-compra').dataset.valorBase), parcelas);
            break;
        case 'boleto':
            valorFinal = calcularValorFinalBoleto(parseFloat(document.getElementById('total-compra').dataset.valorBase));
            break;
    }

    alert(`Compra finalizada com sucesso!\n\nNome: ${nome}\nCPF: ${cpf}\nEmail: ${email}\nTelefone: ${telefone}\nMétodo de Pagamento: ${metodoPagamento}\nValor Final: R$ ${valorFinal.toFixed(2)}`);
}

// Exemplos de funções de cálculo para os métodos de pagamento
function calcularValorFinalPix(valorBase) {
    return valorBase - (valorBase * 0.05); // 5% de desconto para PIX
}

function calcularValorFinalCartao(valorBase, parcelas) {
    return valorBase; // Sem desconto para cartão
}

function calcularValorFinalBoleto(valorBase) {
    return valorBase; // Sem desconto para boleto
}
