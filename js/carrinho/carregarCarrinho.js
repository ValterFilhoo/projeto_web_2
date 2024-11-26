document.addEventListener('DOMContentLoaded', function() {
    function getUserId() {
        return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data
    }

    const userId = getUserId();
    console.log(`Usuário autenticado com ID: ${userId}`);

    carregarItensDoCarrinho(userId);
    
});

function carregarItensDoCarrinho(userId) {

    const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);
    const carrinhoProdutos = document.getElementById('carrinho-produtos');
    const totalCarrinho = document.getElementById('total-carrinho');

    console.log('Carregando itens do carrinho:', carrinho);

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        carrinhoProdutos.innerHTML = ''; // Limpa o contêiner antes de adicionar os produtos

        let total = 0;

        // Itera sobre cada produto no carrinho e cria elementos HTML para exibi-los
        carrinho.forEach(produto => {
            console.log('Produto encontrado no carrinho:', produto);

            if (produto && typeof produto.valorProduto === 'number' && typeof produto.quantidade === 'number') {
                const produtoTr = document.createElement('tr');
                produtoTr.classList.add('cart-product');
                produtoTr.innerHTML = `
                    <td>
                        <div class="produto-info">
                            <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}" width="60">
                            <span>${produto.nomeProduto}</span>
                        </div>
                    </td>
                    <td class="produto-preco">R$ ${produto.valorProduto.toFixed(2)}</td>
                    <td class="produto-quantidade">
                        <button class="diminuir" data-id="${produto.id}">-</button>
                        <input type="number" value="${produto.quantidade}" min="1" class="input-preco" data-id="${produto.id}">
                        <button class="aumentar" data-id="${produto.id}">+</button>
                    </td>
                    <td>
                        <button class="remover" data-id="${produto.id}">Remover</button>
                    </td>
                `;
                carrinhoProdutos.appendChild(produtoTr);

                total += produto.valorProduto * produto.quantidade;

                // Adiciona evento ao botão "Remover"
                produtoTr.querySelector('.remover').addEventListener('click', function() {
                    removerDoCarrinho(userId, produto.id);
                });

                // Adiciona evento ao botão "Diminuir"
                produtoTr.querySelector('.diminuir').addEventListener('click', function() {
                    alterarQuantidadeProduto(userId, produto.id, -1);
                });

                // Adiciona evento ao botão "Aumentar"
                produtoTr.querySelector('.aumentar').addEventListener('click', function() {
                    alterarQuantidadeProduto(userId, produto.id, 1);
                });

                // Adiciona evento ao input de quantidade
                produtoTr.querySelector('.input-preco').addEventListener('change', function() {
                    alterarQuantidadeProduto(userId, produto.id, parseInt(this.value) - produto.quantidade);
                });
            } else {
                console.error('Produto inválido ou propriedades ausentes:', produto);
                console.log('ID:', produto.id);
                console.log('Nome:', produto.nomeProduto);
                console.log('Valor:', produto.valorProduto);
                console.log('Quantidade:', produto.quantidade);
                console.log('Imagem:', produto.imagemProduto);
            }
        });

        totalCarrinho.textContent = `Total: R$ ${total.toFixed(2)}`;
    } else {
        carrinhoProdutos.innerHTML = '<tr><td colspan="4">O carrinho está vazio.</td></tr>';
        totalCarrinho.textContent = 'Total: R$ 0,00';
    }
}

function removerDoCarrinho(userId, id) {
    const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);
    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        const index = carrinho.findIndex(produto => produto.id === id);
        if (index !== -1) {
            carrinho.splice(index, 1); // Remove o produto do carrinho
            localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho)); // Atualiza o localStorage
            carregarItensDoCarrinho(userId); // Atualiza a exibição do carrinho
        }
    }
}

function alterarQuantidadeProduto(userId, id, quantidade) {
    const chaveCarrinho = `carrinho_${userId}`;
    let carrinho = localStorage.getItem(chaveCarrinho);
    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        const produto = carrinho.find(produto => produto.id === id);
        if (produto) {
            produto.quantidade += quantidade;
            if (produto.quantidade < 1) {
                produto.quantidade = 1; // Garante que a quantidade mínima é 1
            }
            localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));
            carregarItensDoCarrinho(userId); // Recarrega os itens do carrinho após a alteração
        }
    }
}
