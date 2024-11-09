document.getElementById('formularioDeLogin').addEventListener('submit', async (evento) => {

    evento.preventDefault();

    const dadosFormulario = new FormData(evento.target);
    const email = dadosFormulario.get('email');
    const senha = dadosFormulario.get('senha');

    try {

        const resposta = await fetch('../../PHP/processarFormularios/autenticacao/autenticarUsuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ email, senha })
        });

        const resultado = await resposta.json();

        if (resultado.status === 'sucesso') {

            alert(resultado.mensagem);
            // Redirecionar para outra página ou realizar outra ação
            

        } else {

            alert(resultado.mensagem);

        }

    } catch (erro) {

        console.error('Erro na autenticação:', erro);
        alert('Ocorreu um erro na autenticação. Tente novamente mais tarde.');

    }

});
