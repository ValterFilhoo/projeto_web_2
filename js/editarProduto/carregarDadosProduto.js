// Obtém o ID do produto da URL
const urlParams = new URLSearchParams(window.location.search);
const produtoId = urlParams.get('id');

if (produtoId) {
    // Faz uma requisição para buscar os dados do produto
    fetch(`../PHP/processarFormularios/produto/buscarProduto.php?id=${produtoId}`)
        .then(resposta => {
            if (!resposta.ok) {
                throw new Error('Erro ao buscar dados do produto.');
            }
            return resposta.json(); // Converte a resposta para JSON
        })
        .then(dados => {
            if (dados.status === 'sucesso') {
                carregarDadosNoFormulario(dados.produto); // Chama função para carregar dados no formulário
            } else {
                alert(dados.mensagem);
            }
        })
        .catch(erro => {
            console.error('Erro:', erro);
            alert('Ocorreu um erro ao carregar os dados do produto.');
        });
} else {
    alert('ID do produto não encontrado na URL.');
}

// Função para carregar dados do produto selecionado e preencher o formulário
function carregarDadosNoFormulario(produto) {
    document.getElementById('nome-produto').value = produto.nome;
    document.getElementById('descricao-produto').value = produto.descricao;
    document.getElementById('valor-produto').value = produto.valor;
    document.getElementById('quantidade-produto').value = produto.quantidade;
    document.getElementById('categoria-produto').value = produto.categoria;
    atualizarTipos(); // Atualiza os tipos com base na categoria
    document.getElementById('tipo-produto').value = produto.tipo;

    if (produto.tipo === 'Kit') {
        carregarProdutosPredefinidos(); // Carrega os produtos do kit
        // Preencher os campos específicos do kit
        produto.kit.forEach(item => {
            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('produto-kit-item');

            const labelNomeProduto = document.createElement('label');
            labelNomeProduto.textContent = item.nome;
            produtoDiv.appendChild(labelNomeProduto);

            const inputQuantidade = document.createElement('input');
            inputQuantidade.type = 'number';
            inputQuantidade.name = `quantidade_${item.nome}`;
            inputQuantidade.placeholder = 'Quantidade';
            inputQuantidade.min = '0';
            inputQuantidade.value = item.quantidade;
            inputQuantidade.addEventListener('input', calcularValorKit);
            produtoDiv.appendChild(inputQuantidade);

            const inputValor = document.createElement('input');
            inputValor.type = 'number';
            inputValor.name = `valor_${item.nome}`;
            inputValor.placeholder = 'Valor Individual';
            inputValor.step = '0.01';
            inputValor.min = '0';
            inputValor.value = item.valor;
            inputValor.addEventListener('input', calcularValorKit);
            produtoDiv.appendChild(inputValor);

            const inputTipo = document.createElement('input');
            inputTipo.type = 'text';
            inputTipo.name = `tipo_${item.nome}`;
            inputTipo.value = item.tipo;
            inputTipo.readOnly = true; // Tornar o campo somente leitura
            produtoDiv.appendChild(inputTipo);

            document.getElementById('produtos-kit-detalhes').appendChild(produtoDiv);
        });
        document.getElementById('campos-kit').style.display = 'block';
    }
}
