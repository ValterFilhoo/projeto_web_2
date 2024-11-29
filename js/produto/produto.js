document.addEventListener('DOMContentLoaded', function() {
    
    const containerProduto = document.getElementById('product-container');
    const notificacao = document.getElementById('notificacao'); // Elemento de notificação
    const userId = getUserId(); // Função para obter o ID do usuário autenticado

    function mostrarNotificacao(mensagem, duracao = 3000) {
        notificacao.textContent = mensagem;
        notificacao.classList.add('mostrar');
        notificacao.classList.remove('esconder');

        setTimeout(() => {
            notificacao.classList.add('esconder');
            notificacao.classList.remove('mostrar');
        }, duracao);
    }

    // Função para obter o ID do usuário autenticado
    function getUserId() {
        return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data
    }

    // Função para carregar itens do carrinho
    function carregarItensDoCarrinho(userId) {

        const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
        let carrinho = localStorage.getItem(chaveCarrinho);
        const carrinhoItens = document.getElementById('carrinho-itens');
        const totalCarrinho = document.getElementById('total-carrinho');

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

    // Função para adicionar produto ao carrinho
    function adicionarAoCarrinho(userId, produto) {

        const chaveCarrinho = `carrinho_${userId}`; // Cria uma chave única para o carrinho do usuário
        let carrinho = localStorage.getItem(chaveCarrinho);

        if (carrinho) {
            carrinho = JSON.parse(carrinho);
        } else {
            carrinho = [];
        }

        // Verifica se o produto já está no carrinho
        const produtoExistente = carrinho.find(item => item.id === produto.id);

        if (produtoExistente) {
            produtoExistente.quantidade += 1; // Incrementa a quantidade se o produto já estiver no carrinho
        } else {
            // Adiciona o novo produto ao carrinho
            carrinho.push({ ...produto, quantidade: 1 });
        }

        // Atualiza o localStorage com o carrinho atualizado
        localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));

        mostrarNotificacao('Produto adicionado ao carrinho com sucesso!'); // Exibir notificação em vez de alert

        // Atualiza o modal do carrinho
        carregarItensDoCarrinho(userId);

    }

    // Obter o ID do produto a partir da URL
    const params = new URLSearchParams(window.location.search);
    const idProduto = params.get('idProduto'); // Pega o valor do parâmetro 'id'

    if (!idProduto) {
        console.error('ID do produto não especificado na URL');
        return;
    }

    // Construir a URL para buscar o produto específico no back.
    const url = `buscarProdutos/buscarProdutoPorId.php?id=${idProduto}`;

    // Função para exibir o produto.
    function exibirProduto(produto) {

        const cartaoProduto = document.createElement('div');
        cartaoProduto.classList.add('product-card');
        cartaoProduto.innerHTML = `
            <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}" class="product-image">
            <h1>${produto.nomeProduto}</h1>
            <p class="price">R$ ${produto.valorProduto.toFixed(2)}</p>
            <p><strong>Quantidade:</strong> ${produto.quantidade}</p>
            <p><strong>Categoria:</strong> ${produto.categoria}</p>
            <p><strong>Tipo de Produto:</strong> ${produto.tipoProduto}</p>
            <p class="description">${produto.descricaoProduto.trim()}</p>
            <button class="add-to-cart-btn" data-id="${produto.id}">Adicionar ao Carrinho</button>
        `;

        // Se o produto for um kit, exibe os produtos do kit.
        if (produto.tipoProduto === 'Kit' && Array.isArray(produto.produtosKit) && produto.produtosKit.length > 0) {

            const produtosKitDiv = document.createElement('div');
            produtosKitDiv.classList.add('produtos-kit');

            const tituloKit = document.createElement('h2');
            tituloKit.textContent = 'Produtos do Kit';
            produtosKitDiv.appendChild(tituloKit);

            produto.produtosKit.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('produto-kit-item');
                itemDiv.innerHTML = `
                    <p><strong>Produto:</strong> ${item.nomeProduto}</p> <!-- Acesso adequado ao nome do produto -->
                    <p><strong>Quantidade:</strong> ${item.quantidade}</p>
                    <p><strong>Valor:</strong> R$ ${item.valorProduto.toFixed(2)}</p>
                `;
                produtosKitDiv.appendChild(itemDiv);
            });

            cartaoProduto.insertBefore(produtosKitDiv, cartaoProduto.querySelector('.add-to-cart-btn'));

        }

        containerProduto.appendChild(cartaoProduto);

        // Adicionar evento ao botão "Adicionar ao Carrinho"
        const addToCartButton = cartaoProduto.querySelector('.add-to-cart-btn');
        addToCartButton.addEventListener('click', function() {
            adicionarAoCarrinho(userId, produto);
        });

    }

    // Fazer a requisição para a URL do arquivo php que busca os dados do produto especifico pelo id.
    fetch(url)

        .then(response => {

            if (!response.ok) {
                throw new Error('Erro na resposta da rede');
            }

            return response.json();

        })
        .then(dados => {

            if (dados.status === "sucesso" && dados.entidade) {
                exibirProduto(dados.entidade);
            } else {
                console.error('Produto não encontrado');
            }

        })
        .catch(erro => {
            console.error('Erro ao carregar o produto:', erro);
        });

});
