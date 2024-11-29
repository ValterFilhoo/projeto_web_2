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
  
    const userId = getUserId(); // Função para obter o ID do usuário autenticado
  
    // Código específico para carregar produtos dessa página
    fetch('../PHP/buscarProdutos/buscarTodosProdutos.php?tipo=Produtos')
      .then(resposta => resposta.json())
      .then(dados => {

        if (dados.status === 'sucesso') {

          const containerProdutos = document.getElementById('produtos'); // Contêiner onde os produtos serão adicionados
          const numeroParcelas = 6; // Número de parcelas para calcular o valor do produto parcelado
          const tipoConta = dados.tipoConta; // Armazena o tipo de conta do usuário autenticado
  
          // Itera sobre cada produto retornado
          dados.entidades.forEach(produto => {
            const valorParcela = (produto.valorProduto / numeroParcelas).toFixed(2); // Cálculo do valor da parcela
            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('notebook'); // Classe 'notebook' para estilização
  
            // Criação do HTML interno do produto com botões de ações
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
  
            // Adiciona o produto ao contêiner de produtos
            containerProdutos.appendChild(produtoDiv);
  
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
          console.error('Erro ao carregar produtos:', dados.mensagem); // Exibe a mensagem de erro no console
        }
      })
      .catch(erro => console.error('Erro ao carregar produtos:', erro)); // Exibe erros de rede no console
  
  });
  
  // Função fictícia para obter o ID do usuário autenticado
  function getUserId() {
    return document.body.getAttribute('data-user-id'); // Pega o ID do atributo data
  }
  
  function adicionarAoCarrinho(userId, produto) {

    const chaveCarrinho = `carrinho_${userId}`; // Cria uma chave única para o carrinho do usuário
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

  }
  