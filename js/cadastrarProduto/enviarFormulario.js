document.addEventListener('DOMContentLoaded', () => {
    const notificacao = document.getElementById('notificacao');

    function mostrarNotificacao(mensagem, duracao = 3000) {
        notificacao.textContent = mensagem;
        notificacao.classList.add('mostrar');
        notificacao.classList.remove('esconder');

        setTimeout(() => {
            notificacao.classList.add('esconder');
            notificacao.classList.remove('mostrar');
        }, duracao);
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir o comportamento padrão do formulário

        let arquivoImagem = document.getElementById('imagem-produto');

        if (arquivoImagem.files.length === 0) {
            mostrarNotificacao('Por favor, selecione uma imagem.');
            return;
        }

        let formData = new FormData(this); // Criar um FormData com os dados do formulário

        // Adicionar os produtos do kit ao formData
        const tipoProduto = document.getElementById('tipo-produto').value;
        console.log(`Tipo do produto principal: ${tipoProduto}`); // Log do tipo do produto principal

        if (tipoProduto === 'Kit') {
            formData.append('tipoPrincipal', tipoProduto); // Tipo do produto principal (Kit)
            const produtosKitDetalhes = document.getElementById('produtos-kit-detalhes');
            const produtosKitItens = produtosKitDetalhes.querySelectorAll('.produto-kit-item');

            produtosKitItens.forEach((produtoDiv, index) => {
                const quantidadeInput = produtoDiv.querySelector(`input[name^="quantidade_"]`);
                const valorInput = produtoDiv.querySelector(`input[name^="valor_"]`);
                const tipoInput = produtoDiv.querySelector(`select[name^="tipo_"]`); // Usando um <select> para tipo do produto no kit
                const produtoNome = produtoDiv.querySelector('label').textContent;

                if (quantidadeInput && valorInput && tipoInput) {
                    formData.append(`kit[produtos][${index}][nome]`, produtoNome);
                    formData.append(`kit[produtos][${index}][quantidade]`, quantidadeInput.value);
                    formData.append(`kit[produtos][${index}][valor]`, valorInput.value);
                    formData.append(`kit[produtos][${index}][tipo]`, tipoInput.value);

                    // Adicionar logs de depuração
                    console.log(`Produto ${index}: Nome = ${produtoNome}, Quantidade = ${quantidadeInput.value}, Valor = ${valorInput.value}, Tipo = ${tipoInput.value}`);
                }
            });
        }

        fetch('../PHP/processarFormularios/produto/cadastrarProduto.php', {
            method: 'POST',
            body: formData
        })
        .then(resposta => resposta.json())
        .then(dados => {
            if (dados.status === 'sucesso') {
                mostrarNotificacao(dados.mensagem);
               // setTimeout(() => {
                   // window.location.href = './index.php';
              //  }, 2000); // Redireciona após 2 segundos
            } else {
                mostrarNotificacao(dados.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarNotificacao('Ocorreu um erro ao cadastrar o produto. Tente novamente mais tarde.');
        });
    });
});
