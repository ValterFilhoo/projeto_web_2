document.addEventListener('DOMContentLoaded', function() {

    // Faz uma requisição para buscar produtos da categoria de Display no servidor.
    fetch('../PHP/buscarProdutos/buscarProdutosCategoria.php?categoria=Sensores')
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

            // Obtém o contêiner onde os produtos serão exibidos.
            const containerProdutos = document.getElementById('produtos');

            // Limpa o contêiner antes de adicionar novos produtos.
            containerProdutos.innerHTML = '';

            // Verifica se o status da resposta JSON é 'sucesso'.
            if (dados.status === 'sucesso') {

                // Verifica se há produtos retornados na resposta JSON.
                if (dados.produtos.length > 0) {

                    // Itera sobre cada produto retornado.
                    dados.produtos.forEach(produto => {

                        // Calcula o valor da parcela em 6x.
                        const valorParcela = (produto.valorProduto / 6).toFixed(2);
                        // Cria um novo elemento div para o produto.
                        const produtoDiv = document.createElement('div');

                        // Adiciona a classe CSS 'notebook' ao elemento div.
                        produtoDiv.classList.add('notebook');

                        // Define o HTML interno do elemento div com as informações do produto.
                        produtoDiv.innerHTML = `
                            <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                            <h1>${produto.nomeProduto}</h1>
                            <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                            <p>até 6x de R$ ${valorParcela}</p>
                            <a href="produtoDetalhes.php?id=${produto.id}">Comprar agora</a>
                        `;

                        // Adiciona o elemento div ao contêiner de produtos.
                        containerProdutos.appendChild(produtoDiv);

                    });

                } else {

                    // Se não há produtos retornados, exibe a mensagem informada pelo servidor.
                    containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`;

                }

            } else {
                
                // Se o status da resposta JSON não for 'sucesso', exibe a mensagem de erro informada pelo servidor.
                containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`;

            }

        })
        .catch(erro => {

            // Captura e trata qualquer erro de rede ou de resposta.
            const containerProdutos = document.getElementById('produtos');

            // Exibe a mensagem de erro informada pelo servidor
            containerProdutos.innerHTML = `<p>${erro.message}</p>`;

        });
        
});
