<?php

require_once __DIR__ . '/crudAbstractTemplateMethod.php';
require_once __DIR__ . '/../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php'; // Ajuste o caminho conforme necessário

class CrudItemPedido extends CrudTemplateMethod {

    public function sqlCriar(): string {
        return "INSERT INTO pedido_produto (idPedido, idProduto, quantidade, valorItem) VALUES (?, ?, ?, ?)";
    }

    public function sqlLer(): string {
        return "SELECT * FROM pedido_produto WHERE idPedido = ? AND idProduto = ?";
    }

    public function sqlAtualizar(): string {
        return "UPDATE pedido_produto SET quantidade = ?, valorItem = ? WHERE idPedido = ? AND idProduto = ?";
    }

    public function sqlDeletar(): string {
        return "DELETE FROM pedido_produto WHERE idPedido = ? AND idProduto = ?";
    }

    public function sqlListar(): string {
        return "SELECT * FROM pedido_produto";
    }

    public function vincularParametros($declaracao, $entidade, $operacao): void {

        switch ($operacao) {

            case "Criar":
                $idPedido = $entidade->getIdPedido();
                $idProduto = $entidade->getIdProduto();
                $quantidade = $entidade->getQuantidade();
                $valorItem = $entidade->getValor();
                $declaracao->bind_param("iiid", $idPedido, $idProduto, $quantidade, $valorItem);
                break;

            case "Ler":
            case "Deletar":
                $idPedido = $entidade->getIdPedido();
                $idProduto = $entidade->getIdProduto();
                $declaracao->bind_param("ii", $idPedido, $idProduto);
                break;

            case "Atualizar":
                $quantidade = $entidade->getQuantidade();
                $valorItem = $entidade->getValor();
                $idPedido = $entidade->getIdPedido();
                $idProduto = $entidade->getIdProduto();
                $declaracao->bind_param("idii", $quantidade, $valorItem, $idPedido, $idProduto);
                break;
        }

    }

    public function obterCaminhoImagemSeNecessario($id) {
        throw new Exception("Esta classe não pode usar este método.");
    }
    
    public function excluirImagemSeExistir($caminhoImagem) {
        throw new Exception("Esta classe não pode usar este método.");
    }
    
}
