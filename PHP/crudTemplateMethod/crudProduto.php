<?php 

require_once __DIR__ . '/../arquivosFactoryMethod/product.php';
require_once __DIR__ . '/crudAbstractTemplateMethod.php';

class CrudProduto extends CrudTemplateMethod {

    public function sqlCriar(): string {
        return "INSERT INTO Produto (imagemProduto, nomeProduto, valorProduto, quantidade, categoria, tipoProduto, descricaoProduto) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    public function sqlLer(): string {
        return "SELECT * FROM Produto WHERE id = ?";
    }

    public function sqlAtualizar(): string {
        return "UPDATE produto SET imagem = ?, nome = ?, valor = ?, categoria = ?, tipo = ?, descricao = ? WHERE idProduto = ?";
    }

    public function sqlDeletar(): string {
        return "DELETE FROM produto WHERE idProduto = ?";
    }

    public function sqlListar(): string {
        return "SELECT * FROM Produto ORDER BY id DESC"; // Ordenando por id decrescente/mais recente.
    }
    
    public function sqlBuscarPorCategoria(): string {
        return "SELECT * FROM Produto WHERE categoria = ? ORDER BY id DESC";
    }

    public function buscarProdutosPorCategoria($categoria) {

        $operacao = "BuscarPorCategoria";

        // SQL para buscar produtos por categoria
        $sql = $this->sqlBuscarPorCategoria();

        // Preparar a declaração
        if ($stmt = $this->conexaoBD->prepare($sql)) {
            // Vinculação dos parâmetros
            $this->vincularParametros($stmt, $categoria, $operacao);

            // Executar a declaração
            $stmt->execute();

            // Obter o resultado
            $resultadoDaBusca = $stmt->get_result();

            $produtosEncontrados = [];

            if ($resultadoDaBusca->num_rows > 0) {

                while ($row = $resultadoDaBusca->fetch_assoc()) {

                    $fabricaConcreta = $this->getFactory('Produtos', $row);
                    
                    if (!$fabricaConcreta) {
                        throw new Exception("Tipo de entidade desconhecido: Produtos");
                    }

                    // Instanciar produto usando a fábrica concreta
                    $produto = $fabricaConcreta->criarProduto(
                        (int)$row['id'], $row['imagemProduto'], $row['nomeProduto'], 
                        (float)$row['valorProduto'], (int)$row['quantidade'], 
                        $row['categoria'], $row['tipoProduto'], $row['descricaoProduto']
                    );

                    $produtosEncontrados[] = $produto;
                }
                return $produtosEncontrados;
            } else {
                echo 'Nenhum produto encontrado para a categoria especificada.';
                return null;
            }
        } else {
            echo "Erro na preparação da declaração: " . $this->conexaoBD->error;
            return null;
        }
    }

    public function vincularParametros($declaracao, $entidade, $operacao): void {
        switch ($operacao) {
            case "Criar":
                $imagem = $entidade->getImagem();
                $nome = $entidade->getNome();
                $valor = $entidade->getValor();
                $quantidade = $entidade->getQuantidade();
                $categoria = $entidade->getCategoria();
                $tipo = $entidade->getTipo();
                $descricao = $entidade->getDescricao();
                $declaracao->bind_param("ssdisss", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao);
                break;

            case "Ler":
                $id = $entidade; // Para a operação de leitura, $entidade é o ID
                $declaracao->bind_param("i", $id);
                break;

            case "Atualizar":
                $imagem = $entidade->getImagem();
                $nome = $entidade->getNome();
                $valor = $entidade->getValor();
                $quantidade = $entidade->getQuantidade();
                $categoria = $entidade->getCategoria();
                $tipo = $entidade->getTipo();
                $descricao = $entidade->getDescricao();
                $id = $entidade->getId();
                $declaracao->bind_param("ssdisssi", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $id);
                break;

            case "Deletar":
                $id = $entidade; // Para a operação de deletar, $entidade é o ID
                $declaracao->bind_param("i", $id);
                break;

            case "BuscarPorCategoria":
                $categoria = $entidade;
                $declaracao->bind_param("s", $categoria);
                break;

            default:
                throw new Exception("Operação desconhecida: $operacao");
        }
    }
}
