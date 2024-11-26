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
  
    document.getElementById('formularioDeLogin').addEventListener('submit', async (evento) => {
      evento.preventDefault(); 
  
      const formulario = evento.target;
      const dadosFormulario = new FormData(formulario);
      const email = dadosFormulario.get('email');
      const senha = dadosFormulario.get('senha');
  
      try {

        const resposta = await fetch('./processarFormularios/autenticacao/autenticarUsuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            email,
            senha
          })

        });
  
        const textoResposta = await resposta.text();
  
        try {

          const resultado = JSON.parse(textoResposta);
  
          if (resultado.status === 'sucesso') {

            mostrarNotificacao(resultado.mensagem);
            setTimeout(() => {
              window.location.href = "./index.php";
            }, 2000); // Redireciona após 2 segundos

          } else {
            mostrarNotificacao(resultado.mensagem);
          }

        } catch (erroParse) {

          console.error('Erro ao parsear JSON:', erroParse);
          mostrarNotificacao('Erro no formato da resposta do servidor. Consulte o console para mais detalhes.');
          
        }
        
      } catch (erro) {

        console.error('Erro na autenticação:', erro);
        mostrarNotificacao('Ocorreu um erro na autenticação. Tente novamente mais tarde.');
      }

    });

  });
  