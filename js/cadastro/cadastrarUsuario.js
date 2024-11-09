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
        const resultado = JSON.parse(textoResposta); // Parse da resposta para JSON.

        if (resultado.status === 'sucesso') {

            alert(resultado.mensagem);
            window.location.href = './index.php'; 

        } else {

            alert(resultado.mensagem);

        }

    } catch (erro) {

        console.error('Erro no cadastro:', erro);
        alert('Ocorreu um erro no cadastro. Tente novamente mais tarde.');

    }
    
});
