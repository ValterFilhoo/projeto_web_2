<?php
    
    class CrudProdutoDaoImplementacao implements CrudProdutoDao {

        private $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia("localhost", "root", "96029958va", "panorama_catuense", 3306)->getConexao();

        }
        
        public function createProduto($produto) {

            // Criando o comando INSERT para cadastrar o produto no banco de dados.
            $sql = "INSERT INTO produto (imagem, nome, valor, quantidade, categoria, tipo, descricao) VALUES ('$produto->imagem', '$produto->nome', $produto->valor, '$produto->quantidade', '$produto->categoria', '$produto->tipo', '$produto->descricao')";


            $resultadoInsert = $this->conexaoBD->query($sql);

            // Verificando se realmente cadastrou o produto no banco.
            // Vai retornar true se a query deu certo.
            if ($resultadoInsert) {

                echo "Cadastro do produto realizado com sucesso.";

                return true;


            } else {


                echo "Erro no cadastro do produto: " . $this->conexaoBD->error;

                return false;

            }

        }

        public function readProduto($idProduto) {

            $sql = "SELECT * FROM produto WHERE id = $idProduto";

            $resultadoDaBusca = $this->conexaoBD->query($sql);

            if ($resultadoDaBusca->num_rows > 0) {

                $produtoEncontrado = $resultadoDaBusca->fetch_assoc();

                echo "Produto encontrado.";

                return $produtoEncontrado;

            } else {

                return null;

            }

        }

        public function readProdutos(){
            
            $sql = "SELECT * FROM produto";

            $resultadoDaBusca = $this->conexaoBD->query($sql);

            if ($resultadoDaBusca->num_rows > 0) {

                $produtoEncontradoS = $resultadoDaBusca->fetch_assoc();

                echo "ProdutoS encontrado.";

                return $produtoEncontradoS;

            } else {

                echo 'Nenhum produto encontrado.';
                
                return null;

            }

        }
    
        public function updateProduto($idProduto, $produto) {

            $sql = "UPDATE produto SET imagem = '$produto->imagem', nome = '$produto->nome', valor = $produto->valor, categoria = '$produto->categoria', tipo = '$produto->tipo', descricao = '$produto->descricao' WHERE id = $idProduto";

            $resultadoEdicao = $this->conexaoBD->query($sql);

            if ($resultadoEdicao) {
                
                echo 'Produto editado com sucesso.';

                return true;


            } else {

                echo 'Erro na edição do produto: ' . $this->conexaoBD->error;

                return false;

            }

        }
    
        public function deleteProduto($idProduto){

            $sql = "DELETE FROM produto WHERE id = $idProduto";

            $resultadoExclusao = $this->conexaoBD->query($sql);

            if ($resultadoExclusao) {
                
                echo 'Produto excluido com sucesso.';

                return true;


            } else {

                echo 'Erro na exclusão do produto: ' . $this->conexaoBD->error;

                return false;

            }


        }



    }

?>