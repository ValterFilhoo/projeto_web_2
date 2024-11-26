document.addEventListener('DOMContentLoaded', (event) => {
  const perfil = document.querySelector('.perfil'); // Seleciona o elemento com a classe "perfil"
  const modal = document.getElementById('perfil-modal'); // Seleciona o modal pelo ID "perfil-modal"
  const fechar = document.getElementsByClassName('close-perfil')[0]; // Seleciona o botão de fechar pelo ID "close-perfil"
  const conteudoModal = document.getElementById('modal-content-dynamic'); // Seleciona o conteúdo dinâmico do modal

  perfil.addEventListener('click', () => {
      // Acessa os atributos de dados do corpo da página
      const estaAutenticado = document.body.getAttribute('data-autenticado') === 'true';
      const tipoUsuario = document.body.getAttribute('data-tipo-usuario');
      
      if (estaAutenticado) {
          let conteudo = `
            <ul>
              <li><a href="../PHP/logout/sairDaConta.php">Sair da conta</a></li>
              <li><a href="listagemPedidos.php">Meus Pedidos</a></li>`;
              
          // Adiciona a opção "Cadastrar Produto" se o usuário for Admin
          if (tipoUsuario === 'Admin') {
            conteudo += `<li><a href="cadastrarProduto.php">Cadastrar Produto</a></li>`;
          }
    
          conteudo += `</ul>`;
          conteudoModal.innerHTML = conteudo;
      } else {
          conteudoModal.innerHTML = `<a href="login.php">Fazer Login</a>`;
      }

      modal.style.display = 'block'; // Exibe o modal
  });

  fechar.addEventListener('click', () => {
      modal.style.display = 'none'; // Fecha o modal
  });

  window.addEventListener('click', (event) => {
      if (event.target == modal) {
          modal.style.display = 'none'; // Fecha o modal ao clicar fora dele
      }
  });
});
