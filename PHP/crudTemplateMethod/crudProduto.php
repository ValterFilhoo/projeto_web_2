<?php 

    require_once __DIR__ . '/../arquivosFactoryMethod/product.php';
    require_once __DIR__ . '/crudAbstractTemplateMethod.php';

    class CrudProduto extends CrudTemplateMethod {

        public function sqlCriar(): string {
            return "INSERT INTO produto (imagemProduto, nomeProduto, valorProduto, quantidade, categoria, tipoProduto, descricaoProduto, produtosKit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        }
        

        public function sqlLer(): string {
            return "SELECT * FROM produto WHERE id = ?";
        }

        public function sqlAtualizar(): string {
            return "UPDATE produto SET imagemProduto = ?, nomeProduto = ?, valorProduto = ?, quantidade = ?, categoria = ?, tipoProduto = ?, descricaoProduto = ?, produtosKit = ? WHERE id = ?";
        }

        public function sqlDeletar(): string {
            return "DELETE FROM produto WHERE id = ?";
        }

        public function sqlListar(): string {
            return "SELECT * FROM produto ORDER BY id DESC"; // Ordenando por id decrescente/mais recente.
        }
        
        public function sqlBuscarPorCategoria(): string {
            return "SELECT * FROM produto WHERE categoria = ? ORDER BY id DESC";
        }

        public function buscarProdutosPorCategoria(string $categoria): array|null {

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
                    return null;
                }

            } else {
                echo "Erro na preparação da declaração: " . $this->conexaoBD->error;
                return null;
            }

        }

       
        public function vincularParametros(mysqli_stmt $declaracao, object|array|int $entidade, string $operacao): void {

            switch ($operacao) {

                case "Criar":
                    $imagem = $entidade->getImagem();
                    $nome = $entidade->getNome();
                    $valor = $entidade->getValor();
                    $quantidade = $entidade->getQuantidade();
                    $categoria = $entidade->getCategoria();
                    $tipo = $entidade->getTipo();
                    $descricao = $entidade->getDescricao();
        
                    if ($tipo === 'Kit') {
                        $produtosKit = json_encode(array_map(function($produto) {
                            return [
                                'id' => $produto->getId(),
                                'imagemProduto' => $produto->getImagem(),
                                'nomeProduto' => $produto->getNome(),
                                'valorProduto' => $produto->getValor(),
                                'quantidade' => $produto->getQuantidade(),
                                'categoria' => $produto->getCategoria(),
                                'tipoProduto' => $produto->getTipo(),
                                'descricaoProduto' => $produto->getDescricao()
                            ];

                        }, $entidade->obterProdutos()));
                        $declaracao->bind_param("ssdissss", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $produtosKit);

                    } else {
                        $produtosKit = null; // Para produtos individuais, `produtosKit` deve ser nulo
                        $declaracao->bind_param("ssdissss", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $produtosKit); // 8 parâmetros
                    }

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
        
                    if ($tipo === 'Kit') {

                        $produtosKit = json_encode(array_map(function($produto): array {
                            return [
                                'id' => $produto->getId(),
                                'imagemProduto' => $produto->getImagem(),
                                'nomeProduto' => $produto->getNome(),
                                'valorProduto' => $produto->getValor(),
                                'quantidade' => $produto->getQuantidade(),
                                'categoria' => $produto->getCategoria(),
                                'tipoProduto' => $produto->getTipo(),
                                'descricaoProduto' => $produto->getDescricao()
                            ];

                        }, $entidade->obterProdutos()));

                        $declaracao->bind_param("ssdissssi", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $produtosKit, $id);

                    } else {
                        $produtosKit = null; // Para produtos individuais, `produtosKit` deve ser nulo
                        $declaracao->bind_param("ssdissssi", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao, $produtosKit, $id); // 9 parâmetros
                    }

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
        
        

        // Método para obter o caminho da imagem do produto
        public function obterCaminhoImagemSeNecessario(int $id): string|null {

            $sqlBuscarImagem = "SELECT imagemProduto FROM NomeDaTabelaProdutos WHERE id = ?";
            
            if ($stmt = $this->conexaoBD->prepare($sqlBuscarImagem)) {

                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultado = $stmt->get_result();
                
                if ($resultado->num_rows > 0) {

                    $produto = $resultado->fetch_assoc();
                    $caminhoRelativo = $produto['imagemProduto'];
                    $caminhoAbsoluto = $_SERVER['DOCUMENT_ROOT'] . '/' . $caminhoRelativo;
                    return $caminhoAbsoluto;

                } else {

                    echo 'Produto não encontrado.';
                    return null;

                }

            } else {

                echo "Erro na preparação da declaração: " . $this->conexaoBD->error;
                return null;

            }

        }
        

        public function excluirImagemSeExistir(string $caminhoImagem): void {

            echo 'Tentando excluir a imagem: ' . $caminhoImagem;
            
            if (!empty($caminhoImagem) && file_exists($caminhoImagem)) {

                if (unlink($caminhoImagem)) {
                    echo 'Imagem do produto excluída com sucesso.';
                } else {
                    echo 'Erro ao excluir a imagem do produto.';
                }

            } else {
                echo 'Caminho da imagem inválido ou arquivo não encontrado.';
            }

        }

    }
