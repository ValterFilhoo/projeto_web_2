?<?php

interface CrudProdutoDao {

    public function createProduto($produto);

    public function readProduto($idProduto);

    public function readProdutos();

    public function updateProduto($idProduto, $produto);

    public function deleteProduto($idProduto);


}

?>