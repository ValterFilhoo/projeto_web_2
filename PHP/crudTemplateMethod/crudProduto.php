<?php 

    class CrudProduto extends CrudTemplateMethod  {

        public function sqlCriar(): string {
            
            // Criando o comando INSERT para cadastrar o produto no banco de dados.
           return  $sql = " INSERT INTO Produto (imagem, nome, valor, quantidade, categoria, tipo, descricao) 
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

                    
                    
                    // Vinculando os parãmetros dos valores da string sql, passando os tipos dos valores e seus valores.
                   

                case "Ler":



            }

           
        }

    }

?>