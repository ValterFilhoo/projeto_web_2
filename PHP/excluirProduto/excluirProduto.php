    <?php


    header('Content-Type: application/json');

    // Incluir os arquivos necessários
    require_once __DIR__ . '/../facade/ProdutoFacade.php';

    session_start(); // Inicia a sessão

    try {

        // Verifica se o ID foi especificado na requisição
        if (!isset($_POST['id'])) {
            throw new Exception("ID não especificado.");
        }

        $id = $_POST['id'];

        // Instanciar a classe ProdutoFacade
        $produtoFacade = new ProdutoFacade();

        // Chamar o método excluirProduto
        $resultado = $produtoFacade->excluirProduto($id);

        // Enviar a resposta como JSON
        echo json_encode($resultado);

    } catch (Exception $excecao) {
        $resposta = ["status" => "erro", "mensagem" => $excecao->getMessage()];
        echo json_encode($resposta);
    }


