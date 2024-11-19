document.addEventListener('DOMContentLoaded', function() {
    
    // Obtém o ID do produto da URL
    const urlParams = new URLSearchParams(window.location.search);
    const produtoId = urlParams.get('id');

    if (produtoId) {

        // Faz uma requisição para buscar os dados do produto.
        fetch(`../PHP/processarFormularios/produto/buscarProduto.php?id=${produtoId}`)
            .then(resposta => {

                if (!resposta.ok) {
                    throw new Error('Erro ao buscar dados do produto.');
                }

                return resposta.json(); // Converte a resposta para JSON.
            })
            .then(dados => {

                if (dados.status === 'sucesso') {

                    carregarDadosNoFormulario(dados.produto);

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

    // Função para exibir a pré-visualização da imagem selecionada
    document.getElementById('imagem-produto').addEventListener('change', function(evento) {

        const arquivo = evento.target.files[0]; // Obter o primeiro arquivo selecionado.

        if (arquivo) {

            const leitor = new FileReader(); // Criar uma instância do FileReader.

            leitor.onload = function(e) {

                const imagemPreview = document.getElementById('imagem-preview');
                const imagemMensagem = document.getElementById('imagem-mensagem');
                imagemPreview.src = e.target.result; // Definir a origem da imagem como o resultado do FileReader.
                imagemPreview.classList.remove('icone'); // Remover a classe de ícone.
                imagemPreview.classList.add('preview'); // Adicionar a classe de pré-visualização.
                imagemMensagem.style.display = 'none'; // Esconder o parágrafo.

            };

            leitor.readAsDataURL(arquivo); // Ler o arquivo como uma URL de dados.

        }

    });

    // Função para submeter o formulário de edição de produto
    document.querySelector('form').addEventListener('submit', function(evento) {

        evento.preventDefault(); // Evita o envio padrão do formulário.

        const formData = new FormData(evento.target); // Cria um objeto FormData com os dados do formulário.
        formData.append('id', produtoId); // Adiciona o ID do produto.

        const inputImagem = document.getElementById('imagem-produto');

        if (inputImagem.files.length === 0 && inputImagem.dataset.imagemExistente) {
            formData.append('imagemExistente', inputImagem.dataset.imagemExistente); // Adiciona a imagem existente.
        }

        fetch('../PHP/processarFormularios/produto/editarProduto.php', {
            method: 'POST',
            body: formData
        })
        .then(resposta => {

            if (!resposta.ok) {
                throw new Error('Erro ao enviar dados do formulário.');
            }
            return resposta.json(); // Converte a resposta para JSON.

        })
        .then(dados => {

            if (dados.status === 'sucesso') {

                alert('Produto editado com sucesso!');

                // Redireciona ou atualiza a página inicial.
                window.location.href = "../PHP/index.php";

            } else {

                alert(dados.mensagem);

            }

        })
        .catch(erro => {
            console.error('Erro:', erro);
            alert('Ocorreu um erro ao enviar os dados do formulário.');
        });

    });

});

// Objeto com os tipos de produtos.
const tipos = {

    Arduino: [
        { value: 'Placa', text: 'Placa' }
    ],

    Display: [
        { value: 'LCD', text: 'LCD' },
        { value: 'LED', text: 'LED' },
        { value: 'OLED', text: 'OLED' }
    ],

    Motor: [
        { value: 'Bomba', text: 'Bomba' },
        { value: 'Motor DC', text: 'Motor DC' }
    ],
    
    RaspberryPI: [
        { value: 'Acessório para RaspberryPi', text: 'Acessório para RaspberryPI' },
        { value: 'Placa para RaspberryPi', text: 'Placa para RaspberryPI' }
    ],

    Sensores: [
        { value: 'Sensor de áudio', text: 'Sensor de Audio' },
        { value: 'Sensor de temperatura', text: 'Sensor de Temperatura' }
    ]

};

function carregarDadosNoFormulario(produto) {

    document.getElementById('nome-produto').value = produto.nomeProduto;
    document.getElementById('valor-produto').value = produto.valorProduto;
    document.getElementById('quantidade-produto').value = produto.quantidade;
    document.getElementById('categoria-produto').value = produto.categoria;
    atualizarTipos(); // Atualiza a lista de tipos de produto com base na categoria.
    document.getElementById('tipo-produto').value = produto.tipoProduto;
    document.getElementById('descricao-produto').value = produto.descricaoProduto;

    // Pré-visualização da imagem do produto.
    const imagemPreview = document.getElementById('imagem-preview');
    const imagemMensagem = document.getElementById('imagem-mensagem');
    imagemPreview.src = `../${produto.imagemProduto}`;
    imagemPreview.classList.remove('icone');
    imagemPreview.classList.add('preview');
    imagemMensagem.style.display = 'none';

    // Armazena a imagem existente para enviar caso não seja alterada.
    document.getElementById('imagem-produto').dataset.imagemExistente = produto.imagemProduto;

};

function atualizarTipos() {

    const categoria = document.getElementById('categoria-produto').value;
    const tipoSelect = document.getElementById('tipo-produto');

    // Limpa os tipos de produto antes de adicionar novos.
    tipoSelect.innerHTML = '';

    if (tipos[categoria]) {
        tipos[categoria].forEach(function(tipo) {
            const option = document.createElement('option');
            option.value = tipo.value;
            option.text = tipo.text;
            tipoSelect.add(option);
        });

    } else {

        const option = document.createElement('option');
        option.value = '';
        option.text = 'Selecione uma categoria primeiro';
        tipoSelect.add(option);

    };

};
