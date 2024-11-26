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
  
    document.getElementById('formularioDeCadastro').addEventListener('submit', async (evento) => {
      evento.preventDefault();
  
      const dadosFormulario = new FormData(evento.target);
      const nome = dadosFormulario.get('nome');
      const cpf = dadosFormulario.get('cpf');
      const celular = dadosFormulario.get('celular');
      const sexo = dadosFormulario.get('sexo');
      const email = dadosFormulario.get('email');
      const senha = dadosFormulario.get('senha');
      const dataNascimento = dadosFormulario.get('data-nascimento');
      const cep = dadosFormulario.get('cep');
      const endereco = dadosFormulario.get('endereco');
      const numeroEndereco = dadosFormulario.get('numero');
      const complemento = dadosFormulario.get('complemento');
      const referencia = dadosFormulario.get('referencia');
      const bairro = dadosFormulario.get('bairro');
      const cidade = dadosFormulario.get('cidade');
      const estado = dadosFormulario.get('estado');
  
      try {

        const resposta = await fetch('./processarFormularios/cadastrarUsuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            nome,
            cpf,
            celular,
            sexo,
            email,
            senha,
            'data-nascimento': dataNascimento,
            cep,
            endereco,
            numero: numeroEndereco,
            complemento,
            referencia,
            bairro,
            cidade,
            estado
          })
        });
  
        const textoResposta = await resposta.text();
  
        try {

          const resultado = JSON.parse(textoResposta);
  
          if (resultado.sucesso) {

            mostrarNotificacao(resultado.mensagem);
            setTimeout(() => {
              window.location.href = './index.php';
            }, 2000); // Redireciona após 2 segundos

          } else {
            mostrarNotificacao(resultado.mensagem);
          }

        } catch (erroParse) {

          console.error('Erro ao parsear JSON:', erroParse);
          mostrarNotificacao('Erro no formato da resposta do servidor. Consulte o console para mais detalhes.');

        }
  
      } catch (erro) {
        console.error('Erro no cadastro:', erro);
        mostrarNotificacao('Ocorreu um erro no cadastro. Tente novamente.');
      }

    });
    
  });
  