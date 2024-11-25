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
                    itensHtml += `<li>
                        <img src="../${item.imagemProduto}" alt="${item.nomeProduto}" width="100" height="100">
                        <p>Nome do Produto: ${item.nomeProduto}</p>
                        <p>Quantidade: ${item.quantidade}</p>
                        <p>Valor Unitário: R$ ${item.valor.toFixed(2)}</p>`;
                    
                    if (item.categoriaProduto) {
                        itensHtml += `<p>Categoria: ${item.categoriaProduto}</p>`;
                    }
                    if (item.tipoProduto) {
                        itensHtml += `<p>Tipo: ${item.tipoProduto}</p>`;
                    }
                    if (item.descricaoProduto) {
                        itensHtml += `<p>Descrição: ${item.descricaoProduto}</p>`;
                    }

                    itensHtml += `</li>`;
                });

                let pagamentoHtml = `
                    <p>Data do Pedido: ${pedido.dataPedido}</p>
                    <p>Tipo de Pagamento: ${pedido.tipoPagamento}</p>
                    <p>Valor: R$ ${pedido.valor.toFixed(2)}</p>`;
                
                if (pedido.numeroCartao) {
                    pagamentoHtml += `<p>Número do Cartão: ${pedido.numeroCartao}</p>`;
                }
                if (pedido.quantidadeParcelas) {
                    pagamentoHtml += `<p>Quantidade de Parcelas: ${pedido.quantidadeParcelas}</p>`;
                }
                if (pedido.valorParcelas) {
                    pagamentoHtml += `<p>Valor por Parcela: R$ ${pedido.valorParcelas.toFixed(2)}</p>`;
                }

                detalhesPedido.innerHTML = `
                    <h2>Detalhes do Pedido</h2>
                    ${pagamentoHtml}
                    <h3>Itens do Pedido</h3>
                    <ul>
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
