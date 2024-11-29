<?php

require_once __DIR__ . '/crudAbstractTemplateMethod.php';
require_once __DIR__ . '/../arquivosFactoryMethod/fabricaItemPedido/itemPedidoConcreteCreator.php'; 

class CrudItemPedido extends CrudTemplateMethod {

    public function sqlCriar(): string {
        return "INSERT INTO pedido_produto (idPedido, idProduto, quantidade, valorItem, produtosKit) VALUES (?, ?, ?, ?, ?)";
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
                $idProduto = $entidade->getId();
                $quantidade = $entidade->getQuantidade();
                $valorItem = $entidade->getValor();
                $produtosKit = null;
    
                if ($entidade->getTipo() === 'Kit') {
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
                    }, $entidade->obterProdutos()));
                }
    
                $declaracao->bind_param("iiids", $idPedido, $idProduto, $quantidade, $valorItem, $produtosKit); // 5 parâmetros
                break;
    
            case "Ler":
            case "Deletar":
                $idPedido = $entidade->getIdPedido();
                $idProduto = $entidade->getId();
                $declaracao->bind_param("ii", $idPedido, $idProduto);
                break;
    
            case "Atualizar":
                $quantidade = $entidade->getQuantidade();
                $valorItem = $entidade->getValor();
                $idPedido = $entidade->getIdPedido();
                $idProduto = $entidade->getId();
                $produtosKit = $entidade->getTipo() === 'Kit' ? json_encode(array_map(function($produto) {
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
                }, $entidade->obterProdutos())) : null;
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
