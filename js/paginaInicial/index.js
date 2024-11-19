document.addEventListener('DOMContentLoaded', function() {

    // Função para buscar produtos dinamicamente do servidor
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
                    produtoDiv.classList.add('notebook');

                    // Criação do HTML interno do produto com botões de ações
                    produtoDiv.innerHTML = `
                        <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                        <h1>${produto.nomeProduto}</h1>
                        <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                        <p>até 6x de R$ ${valorParcela}</p>
                        <a href="produtoDetalhes.php?id=${produto.id}">Comprar agora</a>
                        <div class="botoes-acoes">
                            <button class="btn-remover" data-id="${produto.id}">Remover</button>
                            <button class="btn-editar" data-id="${produto.id}">Editar</button>
                        </div>
                    `;
                    
                    // Adiciona o produto ao contêiner de produtos
                    containerProdutos.appendChild(produtoDiv);

                    // Adiciona eventos aos botões de ação
                    produtoDiv.querySelector('.btn-editar').addEventListener('click', function() {
                        window.location.href = `editarProduto.php?id=${produto.id}`; // Redireciona para a página de edição
                    });

                    produtoDiv.querySelector('.btn-remover').addEventListener('click', function() {
                        if (confirm('Tem certeza que deseja excluir este produto?')) {
                            excluirProduto(produto.id); // Chama a função para excluir o produto
                        }
                    });

                });

            } else {
                console.error('Erro ao carregar produtos:', dados.mensagem); // Exibe a mensagem de erro no console
            };

        })
        .catch(erro => console.error('Erro ao carregar produtos:', erro)); // Exibe erros de rede no console

});

// Função para excluir um produto
function excluirProduto(id) {

    fetch(`../PHP/processarFormularios/produto/excluirProduto.php?id=${id}`, {
        method: 'DELETE'
    })
    .then(resposta => {

        if (!resposta.ok) {
            throw new Error('Erro ao excluir o produto.');
        }
        return response.json();

    })
    .then(dados => {

        if (dados.status === 'sucesso') {
            alert('Produto excluído com sucesso!');
            location.reload(); // Recarrega a página para atualizar a lista de produtos

        } else {
            alert(data.mensagem);
        }

    })
    .catch(error => {

        console.error('Erro:', error);
        alert('Ocorreu um erro ao excluir o produto.');

    });

};
