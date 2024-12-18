document.addEventListener('DOMContentLoaded', function() {

    function getUserId() {
        return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data
    }

    const userId = getUserId();
    carregarItensDoCarrinho(userId);

    // Lógica do modal do carrinho
    const cartIcon = document.querySelector('.cart-icon');
    const cartModal = document.getElementById('carrinho-modal');
    const closeBtn = cartModal.querySelector('.close');

    cartIcon.addEventListener('click', function() {
        cartModal.style.display = 'block';
        // Adicionar um log para verificar se o modal está sendo aberto
        console.log('Carrinho modal aberto');
    });

    closeBtn.addEventListener('click', function() {
        cartModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    });

});

const notificacao = document.getElementById('notificacao');

function mostrarNotificacao(mensagem, duracao = 3000) {
    notificacao.textContent = mensagem;
    notificacao.classList.add('mostrar');
    notificacao.classList.remove('esconder');

    setTimeout(() => {
        notificacao.classList.add('esconder');
        notificacao.classList.remove('mostrar');
    }, duracao);
}

function carregarItensDoCarrinho(userId) {
    const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);
    const carrinhoItens = document.getElementById('carrinho-itens');
    const totalCarrinho = document.getElementById('total-carrinho');

    console.log(`Carregando itens do carrinho para o usuário ${userId}`);

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        carrinhoItens.innerHTML = ''; // Limpa o contêiner antes de adicionar os produtos

        let total = 0;

        // Itera sobre cada produto no carrinho e cria elementos HTML para exibi-los
        carrinho.forEach(produto => {
            if (produto && typeof produto.valorProduto === 'number' && typeof produto.quantidade === 'number') {
                const produtoDiv = document.createElement('div');
                produtoDiv.classList.add('item-carrinho');
                produtoDiv.innerHTML = `
                    <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}" width="50">
                    <div>
                        <h3>${produto.nomeProduto}</h3>
                        <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                        <div class="quantidade-controls">
                            <button class="diminuir-quantidade" data-id="${produto.id}">-</button>
                            <span class="quantidade-produto">${produto.quantidade}</span>
                            <button class="aumentar-quantidade" data-id="${produto.id}">+</button>
                        </div>
                        <button class="remover-item" data-id="${produto.id}">Remover</button>
                    </div>
                `;
                carrinhoItens.appendChild(produtoDiv);

                total += produto.valorProduto * produto.quantidade;

                // Adiciona evento aos botões "Diminuir" e "Aumentar"
                produtoDiv.querySelector('.diminuir-quantidade').addEventListener('click', function() {
                    alterarQuantidadeProduto(userId, produto.id, -1);
                });
                produtoDiv.querySelector('.aumentar-quantidade').addEventListener('click', function() {
                    alterarQuantidadeProduto(userId, produto.id, 1);
                });

                // Adiciona evento ao botão "Remover"
                produtoDiv.querySelector('.remover-item').addEventListener('click', function() {
                    removerDoCarrinho(userId, produto.id);
                    carregarItensDoCarrinho(userId); // Recarrega os itens do carrinho após a remoção
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

        // Define apenas o valor numérico do total
        totalCarrinho.textContent = total.toFixed(2);
    } else {
        carrinhoItens.innerHTML = '<p>O carrinho está vazio.</p>';
        totalCarrinho.textContent = '0,00';
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
        } else {
            console.error('Produto não encontrado no carrinho:', id);
        }
    }
    
}

function adicionarAoCarrinho(userId, produto) {
    const chaveCarrinho = `carrinho_${userId}`; // Cria uma chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
    } else {
        carrinho = [];
    }

    // Verifica se o produto é um kit
    if (produto.tipoProduto === 'Kit' && produto.produtosKit) {
        // Verifica se o kit já está no carrinho
        const kitExistente = carrinho.find(item => item.id === produto.id && item.tipoProduto === 'Kit');

        if (kitExistente) {
            kitExistente.quantidade += 1; // Incrementa a quantidade se o kit já estiver no carrinho
        } else {
            // Adiciona o kit ao carrinho
            carrinho.push({ ...produto, quantidade: 1 });
        }
    } else {
        // Verifica se o produto já está no carrinho
        const produtoExistente = carrinho.find(item => item.id === produto.id && item.tipoProduto !== 'Kit');

        if (produtoExistente) {
            produtoExistente.quantidade += 1; // Incrementa a quantidade se o produto já estiver no carrinho
        } else {
            // Adiciona o novo produto ao carrinho
            carrinho.push({ ...produto, quantidade: 1 });
        }
    }

    // Atualiza o localStorage com o carrinho atualizado
    localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));

    // Atualiza o modal do carrinho
    carregarItensDoCarrinho(userId);

    mostrarNotificacao('Produto adicionado ao carrinho com sucesso!'); // Exibir notificação em vez de alert
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
            carregarItensDoCarrinho(userId); // Atualiza a exibição do carrinho no modal

            mostrarNotificacao('Produto removido do carrinho com sucesso!'); // Exibir notificação em vez de alert
        } else {
            console.error('Produto não encontrado no carrinho:', id);
        }
    }
}
