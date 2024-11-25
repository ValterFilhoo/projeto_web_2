document.addEventListener('DOMContentLoaded', function() {
    const userId = document.body.getAttribute('data-user-id');
    const apiPedidosUrl = document.body.getAttribute('data-api-pedidos-url');

    carregarPedidosUsuario(userId, apiPedidosUrl);
});

// Função para carregar os pedidos do usuário
function carregarPedidosUsuario(userId, apiPedidosUrl) {
    console.info(userId);
    console.info(apiPedidosUrl);
    
    fetch(`${apiPedidosUrl}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                const pedidos = data.pedidos;
                const tabelaPedidos = document.getElementById('tabela-pedidos').getElementsByTagName('tbody')[0];

                // Iterar sobre cada pedido e adicionar à tabela
                pedidos.forEach(pedido => {
                    const pedidoTr = document.createElement('tr');
                    pedidoTr.innerHTML = `
                        <td>${pedido.dataPedido}</td>
                        <td>${pedido.tipoPagamento}</td>
                        <td>R$ ${pedido.valor.toFixed(2)}</td>
                        <td><a href="detalhesPedido.php?id=${pedido.id}">Ver Detalhes</a></td>
                    `;
                    tabelaPedidos.appendChild(pedidoTr);
                });
            } else {
                console.error('Erro ao carregar os pedidos do usuário:', data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        });
}
