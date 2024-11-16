document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir o comportamento padrão do formulário 

    let arquivoImagem = document.getElementById('imagem-produto'); 

    if (arquivoImagem.files.length === 0) { 
        alert('Por favor, selecione uma imagem.'); 
        return; 
    }

    let formData = new FormData(this); // Criar um FormData com os dados do formulário

    fetch('../PHP/processarFormularios/produto/cadastrarProduto.php', {
        method: 'POST',
        body: formData
    })
    .then(resposta => resposta.json())
    .then(dados => {


        if (dados.status === 'sucesso') {

            // Mostrar mensagem de sucesso
            alert(dados.mensagem);
            // Redirecionar para a página inicial
            window.location.href = './index.php';

        } else {

            // Mostrar mensagem de erro
            alert(dados.mensagem);
            
        }

    })
    .catch(error => console.error('Erro:', error)); // Exibe erros de rede no console
});
