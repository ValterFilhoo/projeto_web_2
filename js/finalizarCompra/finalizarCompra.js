document.addEventListener('DOMContentLoaded', function() {
    const userId = document.body.getAttribute('data-user-id');
    const apiUsuarioUrl = document.body.getAttribute('data-api-usuario-url');

    carregarDadosUsuario(userId, apiUsuarioUrl);
    carregarProdutosSelecionados(userId);

    // Adiciona eventos aos métodos de pagamento para recalcular o valor total e as parcelas
    document.querySelectorAll('input[name="pagamento"]').forEach(input => {
        input.addEventListener('change', atualizarValorTotal);
    });

    document.getElementById('parcelas').addEventListener('input', atualizarValorTotal);

    // Adiciona evento ao botão de finalizar compra
    document.getElementById('botao-finalizar').addEventListener('click', function() {
        finalizarCompra(userId);
    });
});

function carregarDadosUsuario(userId, apiUsuarioUrl) {

    console.info(userId)
    console.info(apiUsuarioUrl)
    
    fetch(`${apiUsuarioUrl}?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                const usuario = data.entidade;
                document.getElementById('nome').value = usuario.nomeCompleto;
                document.getElementById('cpf').value = usuario.cpf;
                document.getElementById('email').value = usuario.email;
                document.getElementById('telefone').value = usuario.celular;
            } else {
                console.error('Erro ao carregar os dados do usuário:', data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        });
}

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
            if (produto && typeof produto.valorProduto === 'number' && typeof produto.quantidade === 'number') {
                const produtoTr = document.createElement('tr');
                produtoTr.innerHTML = `
                    <td>
                        <div class="produto-info">
                            <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}" width="60">
                            <span>${produto.nomeProduto}</span>
                        </div>
                    </td>
                    <td>R$ ${produto.valorProduto.toFixed(2)}</td>
                    <td>${produto.quantidade}</td>
                    <td>R$ ${(produto.valorProduto * produto.quantidade).toFixed(2)}</td>
                `;
                produtosSelecionados.appendChild(produtoTr);

                total += produto.valorProduto * produto.quantidade;
            } else {
                console.error('Produto inválido ou propriedades ausentes:', produto);
                console.log('ID:', produto.id);
                console.log('Nome:', produto.nomeProduto);
                console.log('Valor:', produto.valorProduto);
                console.log('Quantidade:', produto.quantidade);
                console.log('Imagem:', produto.imagemProduto);
            }
        });

        totalCompra.dataset.valorBase = total; // Armazena o valor base da compra
        totalCompra.textContent = `Total: R$ ${total.toFixed(2)}`;
    } else {
        produtosSelecionados.innerHTML = '<tr><td colspan="4">Nenhum produto selecionado.</td></tr>';
        totalCompra.textContent = 'Total: R$ 0,00';
    }
}

function adicionarProdutoAoCarrinho(userId, produto) {
    const chaveCarrinho = `carrinho_${userId}`;
    let carrinho = localStorage.getItem(chaveCarrinho);

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
    } else {
        carrinho = [];
    }

    carrinho.push(produto);
    localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));
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

function finalizarCompra(userId) {
    
    const nome = document.getElementById('nome').value;
    const cpf = document.getElementById('cpf').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('telefone').value;
    const metodoPagamento = document.querySelector('input[name="pagamento"]:checked').value;

    // Obter itens do carrinho
    const chaveCarrinho = `carrinho_${userId}`;
    let carrinho = localStorage.getItem(chaveCarrinho);
    carrinho = JSON.parse(carrinho);

    // Coletar detalhes específicos do método de pagamento
    let detalhesPagamento = {};
    if (metodoPagamento === 'cartao_credito') {
        const numeroCartao = document.getElementById('numero-cartao').value;
        const quantidadeParcelas = parseInt(document.getElementById('parcelas').value);
        detalhesPagamento = { numeroCartao, quantidadeParcelas };
    } else if (metodoPagamento === 'pix') {
        const chavePix = document.getElementById('chave-pix').value;
        detalhesPagamento = { chavePix };
    } else if (metodoPagamento === 'boleto') {
        const numeroBoleto = document.getElementById('numero-boleto').value;
        detalhesPagamento = { numeroBoleto };
    }

    // Preparar os dados do pedido
    const pedido = {
        userId,
        nome,
        cpf,
        email,
        telefone,
        metodoPagamento,
        detalhesPagamento,
        produtos: carrinho
    };

    // Enviar os dados para o backend
    fetch('/PHP/finalizarPedido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(pedido)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sucesso') {
            // Limpar carrinho após finalizar a compra
            localStorage.removeItem(chaveCarrinho);
            alert('Compra finalizada com sucesso!');
        } else {
            alert('Erro ao finalizar a compra: ' + data.mensagem);
        }
    })
    .catch(error => {
        console.error('Erro ao finalizar a compra:', error);
        alert('Erro ao finalizar a compra. Tente novamente.');
    });

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
