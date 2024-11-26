<?php

    require_once __DIR__ . '/../arquivosFactoryMethod/fabricaPedido/pedidoConcreteCreator.php'; 
    require_once __DIR__ . '/crudAbstractTemplateMethod.php';

    class CrudPedido extends CrudTemplateMethod {

        public function sqlCriar(): string {
            return "INSERT INTO pedido (idUsuario, dataPedido, tipoPagamento, chavePix, numeroCartao, quantidadeParcelas, numeroBoleto, valor, valorParcelas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
                    pedido.valor,
                    pedido.valorParcelas,  -- Adicionando o campo valorParcelas
                    pedido_produto.idProduto, 
                    pedido_produto.quantidade, 
                    pedido_produto.valorItem,
                    produto.imagemProduto, 
                    produto.nomeProduto, 
                    produto.valorProduto, 
                    produto.categoria, 
                    produto.tipoProduto, 
                    produto.descricaoProduto
                FROM 
                    pedido
                JOIN 
                    pedido_produto ON pedido.id = pedido_produto.idPedido
                JOIN 
                    produto ON pedido_produto.idProduto = produto.id
                WHERE 
                    pedido.id = ?
            ";
        }
        
        public function sqlAtualizar(): string {
            return "UPDATE pedido SET idUsuario = ?, dataPedido = ?, tipoPagamento = ?, chavePix = ?, numeroCartao = ?, quantidadeParcelas = ?, numeroBoleto = ?, valor = ?, valorParcelas = ? WHERE id = ?";
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
                    pedido.valor,
                    pedido.valorParcelas,
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
                    $valorParcelas = $entidade->getValorParcelas();

                    $declaracao->bind_param("issssissd", $idUsuario, $dataPedido, $tipoPagamento, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto, $valor, $valorParcelas);
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
                    $valorParcelas = $entidade->getValorParcelas();
                    $id = $entidade->getId();

                    $declaracao->bind_param("issssissdi", $idUsuario, $dataPedido, $tipoPagamento, $chavePix, $numeroCartao, $quantidadeParcelas, $numeroBoleto, $valor, $valorParcelas, $id);
                    break;
            }

        }

        public function listarPedidosPorUsuario($idUsuario) {

            $sql = "
                SELECT 
                    id, 
                    idUsuario, 
                    dataPedido, 
                    tipoPagamento, 
                    valor,
                    chavePix,
                    numeroCartao,
                    quantidadeParcelas,
                    numeroBoleto,
                    valorParcelas
                FROM 
                    pedido
                WHERE 
                    idUsuario = ?
            ";
            
            $stmt = $this->conexaoBD->prepare($sql);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $pedidos = [];
            $fabricaPedido = new PedidoConcreteCreator(); // Instancia a fábrica de pedidos

            while ($row = $result->fetch_assoc()) {

                // Cria a entidade de pedido usando a fábrica de pedidos
                $pedido = $fabricaPedido->criarPedido(
                    $row['idUsuario'],
                    $row['dataPedido'],
                    $row['tipoPagamento'],
                    [], // Passando um array vazio, pois estamos apenas listando os pedidos, sem detalhes dos itens
                    $row['valor'],
                    $row['chavePix'] ?? null,
                    $row['numeroCartao'] ?? null,
                    $row['quantidadeParcelas'] ?? null,
                    $row['numeroBoleto'] ?? null,
                    $row['valorParcelas'] ?? null
                );

                $pedido->setId($row['id']);
        
                $pedidos[] = [
                    'id' => $pedido->getId(),
                    'dataPedido' => $pedido->getDataPedido(),
                    'tipoPagamento' => $pedido->getTipoPagamento(),
                    'valor' => $pedido->getValor()
                ];

            }
        
            return $pedidos;

        }
        
        

        public function obterCaminhoImagemSeNecessario($id) {
            throw new Exception("Esta classe não pode usar este método.");
        }
        
        public function excluirImagemSeExistir($caminhoImagem) {
            throw new Exception("Esta classe não pode usar este método.");
        }
        
    }
