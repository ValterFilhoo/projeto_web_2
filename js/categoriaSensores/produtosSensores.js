document.addEventListener('DOMContentLoaded', function() {

    // Faz uma requisição para buscar produtos da categoria "Arduino" no servidor.
    fetch('../PHP/buscarProdutos/buscarProdutosCategoria.php?categoria=sensores')
        .then(resposta => {

            // Verifica se a resposta HTTP é bem-sucedida.
            if (!resposta.ok) {

                // Se a resposta não for ok, tenta ler o texto da resposta e lança um erro com a mensagem.
                return resposta.text().then(text => {
                    throw new Error(`Erro na resposta: ${text}`);
                });

            }

            // Se a resposta for ok, converte a resposta para JSON.
            return resposta.json();

        })
        .then(dados => {

            const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão exibidos
            containerProdutos.innerHTML = ''; // Limpa o contêiner antes de adicionar novos produtos

            // Verifica se o status da resposta JSON é 'sucesso'
            if (dados.status === 'sucesso') {

                const tipoConta = dados.tipoConta; // Armazena o tipo de conta do usuário autenticado
                
                if (dados.produtos.length > 0) {

                    dados.produtos.forEach(produto => {

                        const valorParcela = (produto.valorProduto / 6).toFixed(2); // Calcula o valor da parcela em 6x
                        const produtoDiv = document.createElement('div');
                        produtoDiv.classList.add('notebook'); // Adiciona a classe CSS 'notebook' ao elemento div
                        
                        // Define o HTML interno do elemento div com as informações do produto
                        produtoDiv.innerHTML = `
                            <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                            <h1>${produto.nomeProduto}</h1>
                            <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                            <p>até 6x de R$ ${valorParcela}</p>
                            <a href="produtoDetalhes.php?id=${produto.id}">Comprar agora</a>
                            ${tipoConta === 'Admin' ? `
                            <div class="botoes-acoes">
                                <button class="btn-remover" data-id="${produto.id}">Remover</button>
                                <button class="btn-editar" data-id="${produto.id}">Editar</button>
                            </div>` : ''}
                        `;
                        containerProdutos.appendChild(produtoDiv); // Adiciona o elemento div ao contêiner de produtos

                    });

                    if (tipoConta === 'Admin') {

                        // Adiciona eventos de clique aos botões de remover
                        document.querySelectorAll('.btn-remover').forEach(button => {
                            button.addEventListener('click', function() {

                                const produtoId = this.getAttribute('data-id');
                                // Adiciona alerta de confirmação. 
                                const confirmarRemocao = confirm('Você realmente deseja excluir este produto?'); 
                                
                                if (confirmarRemocao) { 
                                    removerProduto(produtoId); 
                                }

                            });
                        });

                        // Adiciona eventos de clique aos botões de editar
                        document.querySelectorAll('.btn-editar').forEach(button => {

                            button.addEventListener('click', function() {

                                const produtoId = this.getAttribute('data-id');
                                 // Enviando o id do produto para a página de edição do produto.
                                 window.location.href = `./editarProduto.php?id=${produtoId}`;

                            });
                            
                        });

                    }

                } else {

                    containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`; // Exibe a mensagem informada pelo servidor

                }
            } else {

                containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`; // Exibe a mensagem de erro informada pelo servidor

            }

        })
        .catch(erro => {

            const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão exibidos
            containerProdutos.innerHTML = `<p>${erro.message}</p>`; // Exibe a mensagem de erro informada pelo servidor

        });

});

// Função para remover um produto
function removerProduto(produtoId) {

    fetch('../PHP/excluirProduto/excluirProduto.php?', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${produtoId}`
    })
    .then(resposta => {

        if (!resposta.ok) {
            return resposta.text().then(text => {
                throw new Error(`Erro na resposta: ${text}`);
            });
        }

        return resposta.json();

    })
    .then(dados => {


        if (dados.status === 'sucesso') {

            alert('Produto removido com sucesso.');

            // Opcional: Remova o produto do DOM sem recarregar a página
            document.querySelector(`.btn-remover[data-id="${produtoId}"]`).closest('.notebook').remove();

        } else {

            alert('Erro ao remover o produto: ' + dados.mensagem);

        }

    })
    .catch(erro => {

        alert('Erro ao tentar remover o produto: ' + erro.message);

    });

}
