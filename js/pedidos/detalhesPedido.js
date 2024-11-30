document.addEventListener('DOMContentLoaded', function() {

    const apiDetalhesPedidoUrl = '../PHP/pedidos/buscarPedidoId.php';
    const urlParams = new URLSearchParams(window.location.search);
    const pedidoId = urlParams.get('id');

    if (pedidoId) {
        carregarDetalhesPedido(apiDetalhesPedidoUrl, pedidoId);
    } else {
        console.error('ID do pedido não encontrado na URL.');
    }

});

// Função para carregar os detalhes do pedido
function carregarDetalhesPedido(apiDetalhesPedidoUrl, pedidoId) {
    fetch(`${apiDetalhesPedidoUrl}?id=${pedidoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                const pedido = data.pedido;
                const detalhesPedido = document.getElementById('detalhes-pedido');

                let itensHtml = '';
                pedido.itens.forEach(item => {
                    itensHtml += `<li class="item-produto">
                        <img src="../${item.imagemProduto}" alt="${item.nomeProduto}" class="imagem-produto">
                        <div class="info-produto">
                            <p class="nome-produto">Nome do Produto: ${item.nomeProduto}</p>
                            <p class="quantidade-produto">Quantidade: ${item.quantidade}</p>
                            <p class="valor-produto">Valor Unitário: R$ ${item.valor.toFixed(2)}</p>`;
                    
                    if (item.categoria) {
                        itensHtml += `<p class="categoria-produto">Categoria: ${item.categoria}</p>`;
                    }
                    if (item.tipoProduto) {
                        itensHtml += `<p class="tipo-produto">Tipo: ${item.tipoProduto}</p>`;
                    }
                    if (item.descricaoProduto) {
                        itensHtml += `<p class="descricao-produto">Descrição: ${item.descricaoProduto}</p>`;
                    }

                    if (item.produtosKit && item.produtosKit.length > 0) {
                        itensHtml += `<div class="detalhes-kit">
                            <p><strong>Produtos do Kit:</strong></p>
                            <ul class="lista-produtos-kit">`;

                        item.produtosKit.forEach(produto => {
                            itensHtml += `<li class="produto-kit">
                                <p>Nome: ${produto.nomeProduto}</p>
                                <p>Quantidade: ${produto.quantidade}</p>
                                <p>Valor: R$ ${produto.valorProduto.toFixed(2)}</p>
                                <p>Categoria: ${produto.categoria}</p>
                                <p>Tipo: ${produto.tipoProduto}</p>
                                <p>Descrição: ${produto.descricaoProduto}</p>
                            </li>`;
                        });

                        itensHtml += `</ul></div>`;
                    }

                    itensHtml += `</div></li>`;
                });

                let pagamentoHtml = `
                    <p class="data-pedido">Data do Pedido: ${pedido.dataPedido}</p>
                    <p class="tipo-pagamento">Tipo de Pagamento: ${pedido.tipoPagamento}</p>
                    <p class="valor-total">Valor: R$ ${pedido.valor.toFixed(2)}</p>`;
                
                if (pedido.numeroCartao) {
                    pagamentoHtml += `<p class="numero-cartao">Número do Cartão: ${pedido.numeroCartao}</p>`;
                }
                if (pedido.quantidadeParcelas) {
                    pagamentoHtml += `<p class="quantidade-parcelas">Quantidade de Parcelas: ${pedido.quantidadeParcelas}</p>`;
                }
                if (pedido.valorParcelas) {
                    pagamentoHtml += `<p class="valor-parcelas">Valor por Parcela: R$ ${pedido.valorParcelas.toFixed(2)}</p>`;
                }

                detalhesPedido.innerHTML = `
                    <h2 class="titulo-detalhes-pedido">Detalhes do Pedido</h2>
                    ${pagamentoHtml}
                    <h3 class="titulo-itens-pedido">Itens do Pedido</h3>
                    <ul class="lista-itens-pedido">
                        ${itensHtml}
                    </ul>
                `;
            } else {
                console.error('Erro ao carregar os detalhes do pedido:', data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        });
}
