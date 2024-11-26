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
  
    // Função para exibir a pré-visualização da imagem selecionada
    document.getElementById('imagem-produto').addEventListener('change', function(evento) {

      const arquivo = evento.target.files[0]; // Obter o primeiro arquivo selecionado
      if (arquivo) {

        const leitor = new FileReader(); // Criar uma instância do FileReader
        leitor.onload = function(e) {
          const imagemPreview = document.getElementById('imagem-preview');
          const imagemMensagem = document.getElementById('imagem-mensagem');
          imagemPreview.src = e.target.result; // Definir a origem da imagem como o resultado do FileReader
          imagemPreview.classList.remove('icone'); // Remover a classe de ícone
          imagemPreview.classList.add('preview'); // Adicionar a classe de pré-visualização
          imagemMensagem.style.display = 'none'; // Esconder o parágrafo
        };

        leitor.readAsDataURL(arquivo); // Ler o arquivo como uma URL de dados
        mostrarNotificacao('Imagem carregada com sucesso.'); // Exibir notificação
      }

    });
  
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
  
    function atualizarTipos() {

      const categoriaSelect = document.getElementById('categoria-produto');
      const tipoSelect = document.getElementById('tipo-produto');
      const categoria = categoriaSelect.value;
  
      // Limpar as opções atuais do select de tipos
      tipoSelect.innerHTML = '';
  
      // Adicionar opções ao select de tipos
      if (tipos[categoria]) {
        tipos[categoria].forEach(function(tipo) {
          const option = document.createElement('option');
          option.value = tipo.value;
          option.text = tipo.text;
          tipoSelect.add(option);
        });

        mostrarNotificacao('Tipos atualizados com sucesso.');

      } else {

        const option = document.createElement('option');
        option.value = '';
        option.text = 'Selecione uma categoria primeiro';
        tipoSelect.add(option);
        mostrarNotificacao('Selecione uma categoria primeiro.');

      }

    }
  
    // Adicionar evento para atualizar tipos quando a categoria mudar
    document.getElementById('categoria-produto').addEventListener('change', atualizarTipos);
  });
  