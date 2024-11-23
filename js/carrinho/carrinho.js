// Função para carregar e exibir os itens do carrinho no modal
function carregarItensDoCarrinho(userId) {
    
    const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);
    const carrinhoItens = document.getElementById('carrinho-itens');
    const totalCarrinho = document.getElementById('total-carrinho');

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
        carrinhoItens.innerHTML = ''; // Limpa o contêiner antes de adicionar os produtos

        // Itera sobre cada produto no carrinho e cria elementos HTML para exibi-los
        carrinho.forEach(produto => {
            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('item-carrinho');
            produtoDiv.innerHTML = `
                <img src="../${produto.imagem}" alt="${produto.nome}" width="50">
                <div>
                    <h3>${produto.nome}</h3>
                    <p>R$ ${produto.valor.toFixed(2)}</p>
                    <div class="quantidade-controls">
                        <button class="diminuir-quantidade" data-id="${produto.id}">-</button>
                        <span class="quantidade-produto">${produto.quantidade}</span>
                        <button class="aumentar-quantidade" data-id="${produto.id}">+</button>
                    </div>
                    <button class="remover-item" data-id="${produto.id}">Remover</button>
                </div>
            `;
            carrinhoItens.appendChild(produtoDiv);

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
            
        });

        // Calcula o total do carrinho
        const total = carrinho.reduce((acc, produto) => acc + produto.valor * produto.quantidade, 0);
        totalCarrinho.textContent = total.toFixed(2);
    } else {
        carrinhoItens.innerHTML = '<p>O carrinho está vazio.</p>';
        totalCarrinho.textContent = '0.00';
    }
}

// Função para alterar a quantidade de um produto no carrinho
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

// Função para adicionar produtos ao carrinho
function adicionarAoCarrinho(userId, id, nome, valor, imagem) {
    const chaveCarrinho = `carrinho_${userId}`; // Cria uma chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);
    if (carrinho) {
        carrinho = JSON.parse(carrinho);
    } else {
        carrinho = [];
    }

    // Verifica se o produto já está no carrinho
    const produtoExistente = carrinho.find(produto => produto.id === id);
    if (produtoExistente) {
        produtoExistente.quantidade += 1; // Incrementa a quantidade se o produto já estiver no carrinho
    } else {
        // Adiciona o novo produto ao carrinho
        carrinho.push({ id, nome, valor, quantidade: 1, imagem });
    }

    // Atualiza o localStorage com o carrinho atualizado
    localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));

    alert('Produto adicionado ao carrinho com sucesso!');
}

// Função para remover produtos do carrinho
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
        }
    }
}
