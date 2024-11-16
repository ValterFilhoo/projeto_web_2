<?php 

    require_once __DIR__ . '/../arquivosFactoryMethod/product.php'; // Caminho corrigido 
    require_once __DIR__ . '/crudAbstractTemplateMethod.php';

    class CrudProduto extends CrudTemplateMethod  {

        public function sqlCriar(): string {
            
            // Criando o comando INSERT para cadastrar o produto no banco de dados.
           return  $sql = " INSERT INTO Produto (imagemProduto, nomeProduto, valorProduto, quantidade, categoria, tipoProduto, descricaoProduto) 
           VALUES (?, ?, ?, ?, ?, ?, ?)";

        }

        public function sqlLer(): string {

            return $sql = "SELECT * FROM Produto WHERE id = ?";

        }

        public function sqlAtualizar(): string {

            return $sql = "UPDATE produto SET imagem = ?, nome = ?, valor = ?, categoria = ?, tipo = ?, descricao = ? WHERE idProduto = ?";

        }
        

        public function sqlDeletar(): string {

            return $sql = "DELETE FROM produto WHERE idProduto = ?";

        }

        public function sqlListar(): string {

            return $sql = "SELECT * FROM Produto";

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
                    
                    // Vinculando os parãmetros dos valores da string sql, passando os tipos dos valores e seus valores.
                    $declaracao->bind_param("ssdisss", $imagem, $nome, $valor, $quantidade, $categoria, $tipo, $descricao);

                case "Ler":



            }

           
        }

    }

?>