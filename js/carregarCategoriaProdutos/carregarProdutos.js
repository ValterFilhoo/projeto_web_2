document.addEventListener('DOMContentLoaded', function() {

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
  
    function getUserId() {
      return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data
    }
  
    const userId = getUserId();
  
    // Função para carregar produtos de uma categoria específica
    function carregarProdutos(categoria) {
      fetch(`../PHP/buscarProdutos/buscarProdutosCategoria.php?categoria=${categoria}`)
          .then(resposta => {
              if (!resposta.ok) {
                  return resposta.text().then(text => {
                      throw new Error(`Erro na resposta: ${text}`);
                  });
              }
              return resposta.json();
          })
          .then(dados => {

              const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão exibidos
              containerProdutos.innerHTML = ''; // Limpa o contêiner antes de adicionar novos produtos
  
              if (dados.status === 'sucesso') {

                  const tipoConta = dados.tipoConta; // Armazena o tipo de conta do usuário autenticado
  
                  if (dados.produtos.length > 0) {

                      dados.produtos.forEach(produto => {

                          const valorParcela = (produto.valorProduto / 6).toFixed(2); // Calcula o valor da parcela em 6x
                          const produtoDiv = document.createElement('div');
                          produtoDiv.classList.add('notebook'); // Adiciona a classe CSS 'notebook' ao elemento div
  
                          produtoDiv.innerHTML = `
                              <a href="./produto.php?idProduto=${produto.id}">
                                  <img src="../${produto.imagemProduto}" alt="${produto.nomeProduto}">
                              </a>
                              <h1>${produto.nomeProduto}</h1>
                              <p>R$ ${produto.valorProduto.toFixed(2)}</p>
                              <p>até 6x de R$ ${valorParcela}</p>
                              <button class="adicionar-carrinho" data-id="${produto.id}">Adicionar ao Carrinho</button>
                              ${tipoConta === 'Admin' ? `
                              <div class="botoes-acoes">
                                  <button class="btn-remover" data-id="${produto.id}">Remover</button>
                                  <button class="btn-editar" data-id="${produto.id}">Editar</button>
                              </div>` : ''}
                          `;
  
                          containerProdutos.appendChild(produtoDiv); // Adiciona o elemento div ao contêiner de produtos
  
                          // Adiciona evento ao botão "Adicionar ao Carrinho"
                          produtoDiv.querySelector('.adicionar-carrinho').addEventListener('click', function() {
                              adicionarAoCarrinho(userId, produto);
                          });
  
                          if (tipoConta === 'Admin') {

                              // Adiciona eventos de clique aos botões de remover
                              document.querySelectorAll('.btn-remover').forEach(button => {
                                  button.addEventListener('click', function() {
                                      const produtoId = this.getAttribute('data-id');
                                      const confirmarRemocao = confirm('Você realmente deseja excluir este produto?'); 
                                      if (confirmarRemocao) { 
                                          removerProduto(produtoId); 
                                      }
                                  });
                              });
  
                              // Adiciona eventos de clique aos botões de editar
                              document.querySelectorAll('.btn-editar').forEach(button => {
                                  button.addEventListener('click', function() {
                                      const produtoId = this.getAttribute('data-id');
                                      window.location.href = `./editarProduto.php?id=${produtoId}`;
                                  });
                              });
                          }

                      });

                  } else {
                      containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`; // Exibe a mensagem informada pelo servidor
                  }

              } else {
                  containerProdutos.innerHTML = `<p>${dados.mensagem}</p>`; // Exibe a mensagem de erro informada pelo servidor
              }

          })
          .catch(erro => {

              const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão exibidos
              containerProdutos.innerHTML = `<p>${erro.message}</p>`; // Exibe a mensagem de erro informada pelo servidor

          });

  }

    // Função para remover o produto
    function removerProduto(produtoId) {
        fetch(`../PHP/excluirProduto/excluirProduto.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${produtoId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                document.querySelector(`.notebook button[data-id="${produtoId}"]`).parentElement.remove();
                mostrarNotificacao('Produto removido com sucesso!');
            } else {
                console.error('Erro ao remover o produto:', data.mensagem);
            }
        })
        .catch(error => console.error('Erro ao remover o produto:', error));
    }

    // Chama a função `carregarProdutos` passando a categoria como parâmetro
    const categoria = window.categoriaProduto; // Categoria passada pela página
    carregarProdutos(categoria);

  });
  
  // Função para obter o ID do usuário autenticado.
  function getUserId() {
    return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data no html da página
  }

  function adicionarAoCarrinho(userId, produto) {

    const chaveCarrinho = `carrinho_${userId}`; // Chave única para o carrinho do usuário
    let carrinho = localStorage.getItem(chaveCarrinho);

    if (carrinho) {
        carrinho = JSON.parse(carrinho);
    } else {
        carrinho = [];
    }

    // Verifica se o produto já está no carrinho
    const produtoExistente = carrinho.find(item => item.id === produto.id);

    if (produtoExistente) {
        produtoExistente.quantidade += 1; // Incrementa a quantidade se o produto já estiver no carrinho
    } else {
        // Adiciona o novo produto ao carrinho
        carrinho.push({ ...produto, quantidade: 1 });
    }

    // Atualiza o localStorage com o carrinho atualizado
    localStorage.setItem(chaveCarrinho, JSON.stringify(carrinho));

    mostrarNotificacao('Produto adicionado ao carrinho com sucesso!'); // Exibir notificação em vez de alert

    // Atualiza o modal do carrinho
    carregarItensDoCarrinho(userId);
    
}
