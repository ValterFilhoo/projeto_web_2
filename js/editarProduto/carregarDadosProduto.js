// Definição das variáveis `tipos` e `produtosPredefinidos`
const tipos = {
    Arduino: [
        { value: 'Placa', text: 'Placa' },
        { value: 'Acessório para Arduino', text: 'Acessório para Arduino' },
        { value: 'Kit', text: 'Kit' }
    ],
    Display: [
        { value: 'LCD', text: 'LCD' },
        { value: 'LED', text: 'LED' },
        { value: 'OLED', text: 'OLED' },
        { value: 'Kit', text: 'Kit' }
    ],
    Motor: [
        { value: 'Bomba', text: 'Bomba' },
        { value: 'Motor DC', text: 'Motor DC' },
        { value: 'Kit', text: 'Kit' }
    ],
    RaspberryPI: [
        { value: 'Acessório para RaspberryPi', text: 'Acessório para RaspberryPI' },
        { value: 'Placa para RaspberryPi', text: 'Placa para RaspberryPI' },
        { value: 'Kit', text: 'Kit' }
    ],
    Sensores: [
        { value: 'Sensor de áudio', text: 'Sensor de Áudio' },
        { value: 'Sensor de temperatura', text: 'Sensor de Temperatura' },
        { value: 'Kit', text: 'Kit' }
    ]
};

const produtosPredefinidos = {
    Arduino: [
        { nome: 'Placa Arduino', tipo: 'Placa' },
        { nome: 'Acessório para Arduino', tipo: 'Acessório para Arduino' }
    ],
    Display: [
        { nome: 'LCD', tipo: 'LCD' },
        { nome: 'LED', tipo: 'LED' },
        { nome: 'OLED', tipo: 'OLED' }
    ],
    Motor: [
        { nome: 'Bomba', tipo: 'Bomba' },
        { nome: 'Motor DC', tipo: 'Motor DC' }
    ],
    RaspberryPI: [
        { nome: 'Acessório para RaspberryPi', tipo: 'Acessório para RaspberryPi' },
        { nome: 'Placa para RaspberryPi', tipo: 'Placa para RaspberryPi' }
    ],
    Sensores: [
        { nome: 'Sensor de Áudio', tipo: 'Sensor de áudio' },
        { nome: 'Sensor de Temperatura', tipo: 'Sensor de temperatura' }
    ]
};

document.addEventListener('DOMContentLoaded', () => {

    const notificacao = document.getElementById('notificacao');
    const tipoSelect = document.getElementById('tipo-produto');
    const camposKit = document.getElementById('campos-kit');
    const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
    const valorKitInput = document.getElementById('valor-produto');
    const salvarProdutoButton = document.getElementById('salvar-produto');

    const categoriaProduto = document.getElementById('categoria-produto');
    const tipoProduto = document.getElementById('tipo-produto');
    const produtosKitCheckboxes = document.getElementById('produtos-kit-checkboxes');

    // Verifique se os elementos existem antes de adicionar event listeners
    if (categoriaProduto) {
        categoriaProduto.addEventListener('change', atualizarTipos);
    }
    if (tipoProduto) {
        tipoProduto.addEventListener('change', atualizarFormulario);
    }
    if (produtosKitCheckboxes) {
        produtosKitCheckboxes.addEventListener('change', atualizarCamposProdutosKit);
    }

    // Chama a função de configuração do preview de imagem
    configurarPreviewImagem();

    if (salvarProdutoButton) {
        salvarProdutoButton.addEventListener('click', (event) => {
            event.preventDefault(); // Evitar o comportamento padrão do botão de enviar formulário

            const imagemInput = document.getElementById('imagem-produto');

            if (imagemInput.files.length > 0) {
                // Uma nova imagem foi selecionada
                const file = imagemInput.files[0];
                console.log('Nova imagem selecionada para envio:', file.name);
                const reader = new FileReader();
                reader.onload = (e) => {
                    console.log('Carregando nova imagem no preview.');
                    document.getElementById('imagem-preview').src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
            
            salvarProduto(); // Chama a função para salvar o produto
        });
    } else {
        console.log('DOMContentLoaded: Botão salvarProdutoButton não encontrado.');
    }

    // Obtém o ID do produto da URL e carrega os dados
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
});

function configurarPreviewImagem() {
    const imagemInput = document.getElementById('imagem-produto');
    const imagemPreview = document.getElementById('imagem-preview');
    const imagemMensagem = document.getElementById('imagem-mensagem');

    if (imagemInput) {
        imagemInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    console.log('Carregando imagem no preview.');
                    imagemPreview.src = e.target.result;
                    imagemPreview.classList.remove('icone'); // Remover a classe icone
                    imagemPreview.classList.add('preview'); // Adicionar a classe preview
                    imagemMensagem.style.display = 'none'; // Esconder a mensagem
                };
                reader.readAsDataURL(file);
            } else {
                imagemPreview.src = '../img/iconAdcionarImagem.png';
                imagemPreview.classList.remove('preview'); // Remover a classe preview
                imagemPreview.classList.add('icone'); // Adicionar a classe icone
                imagemMensagem.style.display = 'block'; // Mostrar a mensagem
            }
        });
    } else {
        console.log('configurarPreviewImagem: Elemento imagemInput não encontrado.');
    }
}



function carregarDadosNoFormulario(produto) {

    if (produto.imagemProduto) {

        const imagemPreview = document.getElementById('imagem-preview');
        imagemPreview.src = `../${produto.imagemProduto}`;
        imagemPreview.classList.remove('icone');
        imagemPreview.classList.add('preview');
        document.getElementById('imagem-mensagem').style.display = 'none';
        document.getElementById('imagem-atual').value = produto.imagemProduto; // Define o caminho da imagem atual no campo oculto
        
    }

    if (produto.nomeProduto) {
        document.getElementById('nome-produto').value = produto.nomeProduto;
    }

    if (produto.descricaoProduto) {
        document.getElementById('descricao-produto').value = produto.descricaoProduto;
    }

    if (produto.valorProduto) {
        document.getElementById('valor-produto').value = produto.valorProduto;
    }

    if (produto.quantidade) {
        document.getElementById('quantidade-produto').value = produto.quantidade;
    }

    if (produto.categoria) {
        document.getElementById('categoria-produto').value = produto.categoria;
        atualizarTipos(); // Atualiza os tipos com base na categoria
    }

    if (produto.tipoProduto) {
        document.getElementById('tipo-produto').value = produto.tipoProduto;
    }

    if (produto.tipoProduto === 'Kit') {

        carregarProdutosPredefinidos(); // Carrega os produtos do kit

        const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes'); // Garantir que produtosKitDetalhes está definido
        produto.produtosKit.forEach(item => {

            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('produto-kit-item');

            const labelNomeProduto = document.createElement('label');
            labelNomeProduto.textContent = item.nomeProduto;
            produtoDiv.appendChild(labelNomeProduto);

            const inputQuantidade = document.createElement('input');
            inputQuantidade.type = 'number';
            inputQuantidade.name = `quantidade_${item.nomeProduto}`;
            inputQuantidade.placeholder = 'Quantidade';
            inputQuantidade.min = '0';
            inputQuantidade.value = item.quantidade || 0;
            inputQuantidade.addEventListener('input', calcularValorKit);
            produtoDiv.appendChild(inputQuantidade);

            const inputValor = document.createElement('input');
            inputValor.type = 'number';
            inputValor.name = `valor_${item.nomeProduto}`;
            inputValor.placeholder = 'Valor Individual';
            inputValor.step = '0.01';
            inputValor.min = '0';
            inputValor.value = item.valorProduto || 0;
            inputValor.addEventListener('input', calcularValorKit);
            produtoDiv.appendChild(inputValor);

            const inputTipo = document.createElement('input');
            inputTipo.type = 'text';
            inputTipo.name = `tipo_${item.nomeProduto}`;
            inputTipo.value = item.tipoProduto || '';
            inputTipo.readOnly = true; // Tornar o campo somente leitura
            produtoDiv.appendChild(inputTipo);

            produtosKitDetalhes.appendChild(produtoDiv);

        });

        // Marcar os checkboxes correspondentes aos produtos do kit
        const produtosKitCheckboxes = document.getElementById('produtos-kit-checkboxes');
        produto.produtosKit.forEach(item => {

            const checkbox = produtosKitCheckboxes.querySelector(`input[value="${item.nomeProduto}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }

        });

        document.getElementById('campos-kit').style.display = 'block';
        document.getElementById('valor-produto').setAttribute('disabled', 'true');
        calcularValorKit(); // Calcular o valor total do kit

    }

}



function atualizarTipos() {

    const categoriaSelect = document.getElementById('categoria-produto');
    const tipoSelect = document.getElementById('tipo-produto');
    const categoria = categoriaSelect.value;

    tipoSelect.innerHTML = '';

    if (tipos[categoria]) {

        tipos[categoria].forEach(function(tipo) {
            const option = document.createElement('option');
            option.value = tipo.value;
            option.text = tipo.text;
            tipoSelect.add(option);
        });

        tipoSelect.disabled = false; // Habilitar o campo de tipo de produto

    } else {

        const option = document.createElement('option');
        option.value = '';
        option.text = 'Selecione uma categoria primeiro';
        tipoSelect.add(option);
        tipoSelect.disabled = true; // Manter desativado
        mostrarNotificacao('Selecione uma categoria primeiro.');

    }

    // Resetar os campos relacionados ao kit
    resetCamposKit();

}

function resetCamposKit() {

    const camposKit = document.getElementById('campos-kit');
    const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
    const valorKitInput = document.getElementById('valor-produto');

    camposKit.style.display = 'none';
    produtosKitDetalhes.innerHTML = '';

    const labelImagem = document.querySelector('label[for="imagem-produto"]');
    const labelNome = document.querySelector('label[for="nome-produto"]');
    const labelValor = document.querySelector('label[for="valor-produto"]');
    const labelQuantidade = document.querySelector('label[for="quantidade-produto"]');
    const labelCategoria = document.querySelector('label[for="categoria-produto"]');
    const labelTipo = document.querySelector('label[for="tipo-produto"]');
    const labelDescricao = document.querySelector('label[for="descricao-produto"]');

    labelImagem.textContent = 'Imagem do Produto';
    labelNome.textContent = 'Nome do Produto';
    labelValor.textContent = 'Valor do Produto';
    labelQuantidade.textContent = 'Quantidade do Produto';
    labelCategoria.textContent = 'Categoria do Produto';
    labelTipo.textContent = 'Tipo do Produto';
    labelDescricao.textContent = 'Descrição do Produto';

    // Reativar o campo de valor se não for kit
    valorKitInput.removeAttribute('disabled');

}
function atualizarFormulario() {

    const tipoProduto = document.getElementById('tipo-produto').value;

    const labelImagem = document.querySelector('label[for="imagem-produto"]');
    const labelNome = document.querySelector('label[for="nome-produto"]');
    const labelValor = document.querySelector('label[for="valor-produto"]');
    const labelQuantidade = document.querySelector('label[for="quantidade-produto"]');
    const labelCategoria = document.querySelector('label[for="categoria-produto"]');
    const labelTipo = document.querySelector('label[for="tipo-produto"]');
    const labelDescricao = document.querySelector('label[for="descricao-produto"]');
    const labelProdutosKit = document.querySelector('label[for="produtos-kit"]');
    const camposKit = document.getElementById('campos-kit');
    const valorKitInput = document.getElementById('valor-produto');

    if (tipoProduto === 'Kit') {

        camposKit.style.display = 'block';
        carregarProdutosPredefinidos(); // Carregar produtos predefinidos para o kit

        labelImagem.textContent = 'Imagem do Kit';
        labelNome.textContent = 'Nome do Kit';
        labelValor.textContent = 'Valor do Kit';
        labelQuantidade.textContent = 'Quantidade de Kits';
        labelCategoria.textContent = 'Categoria do Kit';
        labelTipo.textContent = 'Tipo do Kit';
        labelDescricao.textContent = 'Descrição do Kit';
        labelProdutosKit.textContent = 'Produtos do Kit';

        const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
        produtosKitDetalhes.innerHTML = ''; // Limpar campos anteriores

        // Desativar o campo de valor para kits
        valorKitInput.setAttribute('disabled', 'true');

    } else {

        camposKit.style.display = 'none';

        labelImagem.textContent = 'Imagem do Produto';
        labelNome.textContent = 'Nome do Produto';
        labelValor.textContent = 'Valor do Produto';
        labelQuantidade.textContent = 'Quantidade do Produto';
        labelCategoria.textContent = 'Categoria do Produto';
        labelTipo.textContent = 'Tipo do Produto';
        labelDescricao.textContent = 'Descrição do Produto';

        // Reativar o campo de valor se não for kit
        valorKitInput.removeAttribute('disabled');

    }

}

function salvarProduto() {
    
    const urlParams = new URLSearchParams(window.location.search);
    const produtoId = urlParams.get('id');

    // Criar um objeto FormData para todos os dados do formulário
    const formData = new FormData();
    formData.append('id', produtoId);
    formData.append('nome', document.getElementById('nome-produto').value);
    formData.append('descricao', document.getElementById('descricao-produto').value);
    formData.append('valor', parseFloat(document.getElementById('valor-produto').value));
    formData.append('quantidade', parseInt(document.getElementById('quantidade-produto').value, 10));
    formData.append('categoria', document.getElementById('categoria-produto').value);
    formData.append('tipo', document.getElementById('tipo-produto').value);
    formData.append('imagemExistente', document.getElementById('imagem-atual').value);

    const imagemInput = document.getElementById('imagem-produto');
    if (imagemInput.files.length > 0) {
        formData.append('imagem', imagemInput.files[0]);
    }

    if (document.getElementById('tipo-produto').value === 'Kit') {
        const produtosKit = [];

        document.querySelectorAll('.produto-kit-item').forEach(produtoDiv => {
            const nome = produtoDiv.querySelector('label').textContent;
            const quantidade = parseFloat(produtoDiv.querySelector('input[name^="quantidade_"]').value) || 0;
            const valor = parseFloat(produtoDiv.querySelector('input[name^="valor_"]').value) || 0;
            const tipo = produtoDiv.querySelector('input[name^="tipo_"]').value;
            produtosKit.push({ nomeProduto: nome, quantidade, valorProduto: valor, tipoProduto: tipo });
        });

        formData.append('produtosKit', JSON.stringify(produtosKit));
    }

    fetch('../PHP/processarFormularios/produto/editarProduto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {

        if (!response.ok) {
            throw new Error('Erro ao atualizar o produto.');
        }
        return response.json();

    })
    .then(data => {

        if (data.status === 'sucesso') {

            mostrarNotificacao('Produto editado com sucesso!');
            setTimeout(() => {
                window.location.href = 'index.php'; 
            }, 3000); // 3 segundos de delay

        } else {
            console.log('Erro ao atualizar o produto.');
            alert(data.mensagem);
        }

    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao atualizar o produto.');
    });
}



function calcularValorKit() {
        
    let valorTotal = 0;

    const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
    produtosKitDetalhes.querySelectorAll('.produto-kit-item').forEach(produtoDiv => {
        const quantidade = parseFloat(produtoDiv.querySelector('input[name^="quantidade_"]').value) || 0;
        const valor = parseFloat(produtoDiv.querySelector('input[name^="valor_"]').value) || 0;
        valorTotal += quantidade * valor;
    });

    const valorKitInput = document.getElementById('valor-produto');
    valorKitInput.value = valorTotal.toFixed(2);

}

function carregarProdutosPredefinidos() {

    const categoria = document.getElementById('categoria-produto').value;
    const produtosKitCheckboxes = document.getElementById('produtos-kit-checkboxes');
    produtosKitCheckboxes.innerHTML = ''; // Limpar opções atuais

    if (produtosPredefinidos[categoria]) {

        produtosPredefinidos[categoria].forEach(produto => {
            const checkboxDiv = document.createElement('div');
            checkboxDiv.classList.add('checkbox-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `produto_${produto.nome}`;
            checkbox.value = produto.nome;
            checkbox.name = 'produtos-kit';

            const label = document.createElement('label');
            label.htmlFor = `produto_${produto.nome}`;
            label.textContent = produto.nome;

            checkboxDiv.appendChild(checkbox);
            checkboxDiv.appendChild(label);
            produtosKitCheckboxes.appendChild(checkboxDiv);
        });


    } else {
        mostrarNotificacao('Erro ao carregar produtos específicos.');
    }

}

function atualizarCamposProdutosKit() {

    const produtosSelecionados = Array.from(document.querySelectorAll('input[name="produtos-kit"]:checked')).map(checkbox => checkbox.value);
    const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
    
    // Guardar os valores atuais dos campos de quantidade e valor
    const valoresAtuais = {};

    produtosKitDetalhes.querySelectorAll('.produto-kit-item').forEach(produtoDiv => {
        const nome = produtoDiv.querySelector('label').textContent;
        valoresAtuais[nome] = {
            quantidade: produtoDiv.querySelector(`input[name="quantidade_${nome}"]`).value,
            valor: produtoDiv.querySelector(`input[name="valor_${nome}"]`).value
        };
    });

    produtosKitDetalhes.innerHTML = ''; // Limpar campos anteriores

    produtosSelecionados.forEach(produtoNome => {

        const produtoDiv = document.createElement('div');
        produtoDiv.classList.add('produto-kit-item');

        const labelNomeProduto = document.createElement('label');
        labelNomeProduto.textContent = produtoNome;
        produtoDiv.appendChild(labelNomeProduto);

        const inputQuantidade = document.createElement('input');
        inputQuantidade.type = 'number';
        inputQuantidade.name = `quantidade_${produtoNome}`;
        inputQuantidade.placeholder = 'Quantidade';
        inputQuantidade.min = '0';
        inputQuantidade.value = valoresAtuais[produtoNome] ? valoresAtuais[produtoNome].quantidade : 0;
        inputQuantidade.addEventListener('input', calcularValorKit);
        produtoDiv.appendChild(inputQuantidade);

        const inputValor = document.createElement('input');
        inputValor.type = 'number';
        inputValor.name = `valor_${produtoNome}`;
        inputValor.placeholder = 'Valor Individual';
        inputValor.step = '0.01';
        inputValor.min = '0';
        inputValor.value = valoresAtuais[produtoNome] ? valoresAtuais[produtoNome].valor : 0;
        inputValor.addEventListener('input', calcularValorKit);
        produtoDiv.appendChild(inputValor);

        const inputTipo = document.createElement('input');
        inputTipo.type = 'text';
        inputTipo.name = `tipo_${produtoNome}`;
        inputTipo.value = produtosPredefinidos[document.getElementById('categoria-produto').value].find(produto => produto.nome === produtoNome).tipo;
        inputTipo.readOnly = true; // Tornar o campo somente leitura
        produtoDiv.appendChild(inputTipo);

        produtosKitDetalhes.appendChild(produtoDiv);

    });

    calcularValorKit(); // Calcular o valor total do kit

}


function mostrarNotificacao(mensagem, duracao = 3000) {
        
    const notificacao = document.getElementById('notificacao');
    notificacao.textContent = mensagem;
    notificacao.classList.add('mostrar');
    notificacao.classList.remove('esconder');

    setTimeout(() => {
        notificacao.classList.add('esconder');
        notificacao.classList.remove('mostrar');
    }, duracao);

}

// Evento de salvar produto
document.getElementById('salvar-produto').addEventListener('click', (event) => {
    event.preventDefault(); // Evitar o comportamento padrão do botão de enviar formulário
    salvarProduto();
});

