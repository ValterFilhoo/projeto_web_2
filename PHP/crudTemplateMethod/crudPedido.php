<?php

require_once __DIR__ . '/../arquivosFactoryMethod/fabricaPedido/pedidoConcreteCreator.php'; 
require_once __DIR__ . '/crudAbstractTemplateMethod.php';

class CrudPedido extends CrudTemplateMethod {

    public function sqlCriar(): string {
        return "INSERT INTO pedido (idUsuario, dataPedido, tipoPagamento, chavePix, numeroCartao, quantidadeParcelas, numeroBoleto, valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    public function sqlLer(): string {
        return "
            SELECT 
                pedido.id, 
                pedido.idUsuario, 
                pedido.dataPedido, 
                pedido.tipoPagamento, 
                pedido.chavePix,
                pedido.numeroCartao,
                pedido.quantidadeParcelas,
                pedido.numeroBoleto,
                pedido.valor,  -- Incluindo o valor do pedido
                pedido_produto.idProduto, 
                pedido_produto.quantidade, 
                pedido_produto.valorItem,
                produto.imagemProduto, 
                produto.nomeProduto, 
                produto.valorProduto, 
                produto.quantidade, 
                produto.categoria, 
                produto.tipoProduto, 
                produto.descricaoProduto
            FROM 
                pedido,
                pedido_produto,
                produto
            WHERE 
                pedido.id = pedido_produto.idPedido 
                AND pedido_produto.idProduto = produto.id 
                AND pedido.id = ?
        ";
    }

    public function sqlAtualizar(): string {
        return "UPDATE pedido SET idUsuario = ?, dataPedido = ?, tipoPagamento = ?, chavePix = ?, numeroCartao = ?, quantidadeParcelas = ?, numeroBoleto = ?, valor = ? WHERE id = ?";
    }

    public function sqlDeletar(): string {
        return "DELETE FROM pedido WHERE id = ?";
    }

    public function sqlListar(): string {
        return "
            SELECT 
                pedido.id, 
                pedido.idUsuario, 
                pedido.dataPedido, 
                pedido.tipoPagamento,
                pedido.chavePix,
                pedido.numeroCartao,
                pedido.quantidadeParcelas,
                pedido.numeroBoleto,
                pedido.valor,  -- Incluindo o valor do pedido
                pedido_produto.idProduto, 
                pedido_produto.quantidade, 
                pedido_produto.valorItem,
                produto.imagemProduto, 
                produto.nomeProduto, 
                produto.valorProduto, 
                produto.quantidade, 
                produto.categoria, 
                produto.tipoProduto, 
                produto.descricaoProduto
            FROM 
                pedido,
                pedido_produto,
                produto
            WHERE 
                pedido.id = pedido_produto.idPedido 
                AND pedido_produto.idProduto = produto.id
        ";
    }

    public function vincularParametros($declaracao, $entidade, $operacao): void {
        switch ($operacao) {
            case "Criar":
                $idUsuario = $entidade->getIdUsuario();
                $dataPedido = $entidade->getDataPedido();
                $tipoPagamento = $entidade->getTipoPagamento();
                $chavePix = $entidade->getChavePix();
                $numeroCartao = $entidade->getNumeroCartao();
                $quantidadeParcelas = $entidade->getQuantidadeParcelas();
                $numeroBoleto = $entidade->getNumeroBoleto();
                $valor = $entidade->getValor();

                // Vinculando os parâmetros dos valores da string SQL, passando os tipos dos valores e seus valores.
                $declaracao->bind_param("issssisd", $idUsuario, $dataPedido, $tipoPagamento, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto, $valor);
                break;

            case "Ler":
            case "Deletar":
                $id = $entidade; // Para as operações de leitura e exclusão, $entidade é o ID
                $declaracao->bind_param("i", $id);
                break;

            case "Atualizar":
                $idUsuario = $entidade->getIdUsuario();
                $dataPedido = $entidade->getDataPedido();
                $tipoPagamento = $entidade->getTipoPagamento();
                $chavePix = $entidade->getChavePix();
                $numeroCartao = $entidade->getNumeroCartao();
                $quantidadeParcelas = $entidade->getQuantidadeParcelas();
                $numeroBoleto = $entidade->getNumeroBoleto();
                $valor = $entidade->getValor();
                $id = $entidade->getId();

                // Vinculando os parâmetros dos valores da string SQL, passando os tipos dos valores e seus valores.
                $declaracao->bind_param("issssisd", $idUsuario, $dataPedido, $tipoPagamento, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto, $valor, $id);
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
