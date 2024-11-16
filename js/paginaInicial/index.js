document.addEventListener('DOMContentLoaded', function() {
    // Função para buscar produtos dinamicamente do servidor
    fetch('../PHP/buscarProdutos/buscarTodosProdutos.php?tipo=Produtos')
        .then(resposta => resposta.json())
        .then(dados => {
            console.info(dados); // Log para depuração

            if (dados.status === 'sucesso') {
                const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão adicionados
                const numeroParcelas = 6; // Número de parcelas
                
                // Itera sobre cada produto retornado
                dados.entidades.forEach(produto => {
                    const valorParcela = (produto.valorProduto / numeroParcelas).toFixed(2); // Cálculo do valor da parcela
                    const produtoDiv = document.createElement('div');
                    produtoDiv.classList.add('notebook'); // Adiciona a classe CSS para estilização

                    // Criação do HTML interno do produto
                    produtoDiv.innerHTML = `
                        <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                        <h1>${produto.nomeProduto}</h1>
                        <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                        <p>até 6x de R$ ${valorParcela}</p>
                        <a href="produtoDetalhes.php?id=${produto.id}">Comprar agora</a>
                    `;
                    
                    // Adiciona o produto ao contêiner de produtos
                    containerProdutos.appendChild(produtoDiv);
                });
            } else {
                console.error('Erro ao carregar produtos:', dados.mensagem); // Exibe a mensagem de erro no console
            }
        })
        .catch(erro => console.error('Erro ao carregar produtos:', erro)); // Exibe erros de rede no console
});
