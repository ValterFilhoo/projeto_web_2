<?php

require_once __DIR__ . '/crudAbstractTemplateMethod.php';
require_once __DIR__ . '/../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php'; 

class CrudItemPedido extends CrudTemplateMethod {

    public function sqlCriar(): string {
        return "INSERT INTO pedido_produto (idPedido, idProduto, quantidade, valorItem, produtosKit) VALUES (?, ?, ?, ?, ?)";
    }

    public function sqlLer(): string {
        return "SELECT * FROM pedido_produto WHERE idPedido = ?";
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

    public function vincularParametros($declaracao, $entidadeOuId, $operacao): void {
        switch ($operacao) {
            case "Criar":
                $idPedido = $entidadeOuId->getIdPedido();
                $idProduto = $entidadeOuId->getId();
                $quantidade = $entidadeOuId->getQuantidade();
                $valorItem = $entidadeOuId->getValor();
                $produtosKit = null;
    
                if ($entidadeOuId->getTipo() === 'Kit') {
                    $produtosKit = json_encode(array_map(function($produto) {
                        return [
                            'id' => is_object($produto) ? $produto->getId() : $produto['id'],
                            'imagemProduto' => is_object($produto) ? $produto->getImagem() : $produto['imagemProduto'],
                            'nomeProduto' => is_object($produto) ? $produto->getNome() : $produto['nomeProduto'],
                            'valorProduto' => is_object($produto) ? $produto->getValor() : $produto['valorProduto'],
                            'quantidade' => is_object($produto) ? $produto->getQuantidade() : $produto['quantidade'],
                            'categoria' => is_object($produto) ? $produto->getCategoria() : $produto['categoria'],
                            'tipoProduto' => is_object($produto) ? $produto->getTipo() : $produto['tipoProduto'],
                            'descricaoProduto' => is_object($produto) ? $produto->getDescricao() : $produto['descricaoProduto']
                        ];
                    }, $entidadeOuId->obterProdutos()));
                }
    
                $declaracao->bind_param("iiids", $idPedido, $idProduto, $quantidade, $valorItem, $produtosKit);
                break;
    
            case "Ler":
            case "Deletar":
                $idPedido = $entidadeOuId; // Para leitura, entidadeOuId é o ID
                $declaracao->bind_param("i", $idPedido);
                break;
    
            case "Atualizar":
                $quantidade = $entidadeOuId->getQuantidade();
                $valorItem = $entidadeOuId->getValor();
                $idPedido = $entidadeOuId->getIdPedido();
                $idProduto = $entidadeOuId->getId();
                $produtosKit = $entidadeOuId->getTipo() === 'Kit' ? json_encode(array_map(function($produto) {
                    return [
                        'id' => is_object($produto) ? $produto->getId() : $produto['id'],
                        'imagemProduto' => is_object($produto) ? $produto->getImagem() : $produto['imagemProduto'],
                        'nomeProduto' => is_object($produto) ? $produto->getNome() : $produto['nomeProduto'],
                        'valorProduto' => is_object($produto) ? $produto->getValor() : $produto['valorProduto'],
                        'quantidade' => is_object($produto) ? $produto->getQuantidade() : $produto['quantidade'],
                        'categoria' => is_object($produto) ? $produto->getCategoria() : $produto['categoria'],
                        'tipoProduto' => is_object($produto) ? $produto->getTipo() : $produto['tipoProduto'],
                        'descricaoProduto' => is_object($produto) ? $produto->getDescricao() : $produto['descricaoProduto']
                    ];
                }, $entidadeOuId->obterProdutos())) : null;
                $declaracao->bind_param("idii", $quantidade, $valorItem, $idPedido, $idProduto);
                break;
    
            default:
                throw new Exception("Operação desconhecida: $operacao");
        }
    }

    public function obterCaminhoImagemSeNecessario($id) {
        throw new Exception("Esta classe não pode usar este método.");
    }

    public function excluirImagemSeExistir($caminhoImagem) {
        throw new Exception("Esta classe não pode usar este método.");
    }
}
