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
  
      fetch('../PHP/processarFormularios/produto/cadastrarProduto.php', {
        method: 'POST',
        body: formData
      })
      .then(resposta => resposta.json())
      .then(dados => {

        if (dados.status === 'sucesso') {
          mostrarNotificacao(dados.mensagem);
          setTimeout(() => {
            window.location.href = './index.php';
          }, 2000); // Redireciona após 2 segundos

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
  