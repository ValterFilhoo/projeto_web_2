<?php 

    class CrudProduto extends CrudTemplateMethod  {

        public function sqlCreate($entidade) {
            
            // Criando o comando INSERT para cadastrar o produto no banco de dados.
           return  $sql = "
           INSERT INTO produto (imagem, nome, valor, quantidade, categoria, tipo, descricao) 
           VALUES ('$entidade->imagem', '$entidade->nome', $entidade->valor, '$entidade->quantidade', '$entidade->categoria', '$entidade->tipo', '$entidade->descricao')";

        }

        public function sqlRead($id) {

            return $sql = "SELECT * FROM produto WHERE id = $id";

        }

        public function sqlUpdate($id, $entidade) {

            return $sql = "
            UPDATE produto SET imagem = '$$entidade->imagem', 
            nome = '$entidade->nome', valor = $entidade->valor, 
            categoria = '$entidade->categoria', tipo = '$entidade->tipo',
            descricao = '$entidade->descricao' WHERE idProduto = $id
            ";

        }

        public function sqlDelete($id) {

            return $sql = "DELETE FROM produto WHERE idProduto = $id";

        }

    }

?>