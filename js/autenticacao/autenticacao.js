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

                alert(resultado.mensagem);
                window.location.href = "./index.php";

            } else {

                alert(resultado.mensagem);

            }

        } catch (erroParse) {

            console.error('Erro ao parsear JSON:', erroParse);
            alert('Erro no formato da resposta do servidor. Consulte o console para mais detalhes.');

        }
        
    } catch (erro) {

        console.error('Erro na autenticação:', erro);
        alert('Ocorreu um erro na autenticação. Tente novamente mais tarde.');

    }

});
