
document.addEventListener('DOMContentLoaded', function() {
    const userId = getUserId(); // Função fictícia para obter o ID do usuário autenticado

    // Código específico para carregar produtos dessa página
    fetch('../PHP/buscarProdutos/buscarTodosProdutos.php?tipo=Produtos')
        .then(resposta => resposta.json())
        .then(dados => {
            if (dados.status === 'sucesso') {
                const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão adicionados
                const numeroParcelas = 6; // Número de parcelas para calcular o valor do produto parcelado
                
                // Itera sobre cada produto retornado
                dados.entidades.forEach(produto => {
                    const valorParcela = (produto.valorProduto / numeroParcelas).toFixed(2); // Cálculo do valor da parcela
                    const produtoDiv = document.createElement('div');
                    produtoDiv.classList.add('notebook'); // Classe 'notebook' para estilização

                    // Criação do HTML interno do produto com botões de ações
                    produtoDiv.innerHTML = `
                        <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                        <h1>${produto.nomeProduto}</h1>
                        <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                        <p>até 6x de R$ ${valorParcela}</p>
                        <button class="adicionar-carrinho" data-id="${produto.id}">Adicionar ao Carrinho</button>
                    `;

                    // Adiciona o produto ao contêiner de produtos
                    containerProdutos.appendChild(produtoDiv);

                    // Adiciona evento ao botão "Adicionar ao Carrinho"
                    produtoDiv.querySelector('.adicionar-carrinho').addEventListener('click', function() {
                        adicionarAoCarrinho(userId, produto.id, produto.nomeProduto, produto.valorProduto, produto.imagemProduto);
                    });

                });
                
            } else {
                console.error('Erro ao carregar produtos:', dados.mensagem); // Exibe a mensagem de erro no console
            }
        })
        .catch(erro => console.error('Erro ao carregar produtos:', erro)); // Exibe erros de rede no console

    // Evento para abrir o modal ao clicar no ícone do carrinho
    const cartIcon = document.querySelector('.cart-icon');
    const modal = document.getElementById('carrinho-modal');
    const closeModal = document.querySelector('.modal .close');

    cartIcon.addEventListener('click', function(event) {
        event.preventDefault(); // Previne comportamento padrão do link
        carregarItensDoCarrinho(userId);
        modal.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });
});


// Função fictícia para obter o ID do usuário autenticado
function getUserId() {
    // Esta função deve ser substituída pela lógica real para obter o ID do usuário autenticado
    // Por exemplo, você pode obtê-lo de um cookie, de uma variável JavaScript global ou de uma chamada de API
    return 'usuario123'; // Exemplo de ID de usuário
}
