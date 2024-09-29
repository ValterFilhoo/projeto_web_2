<?php 

    class FacadeServicosDao {

        private $usuarioDao;
        private $produtoDao;

        public function __construct() {

            $this->usuarioDao = new CrudUsuarioDaoImplementacao();
            $this->produtoDao = new CrudProdutoDaoImplementacao();
            
        }

        public function operacoes($tipoOperacao, $dados, $id = null) {

            switch ($tipoOperacao) {

                // Tipos de operação e seus métodos.
                case "Cadastrar usuário":
                    
                    // Instanciar um objeto usuário.
                    $usuario = new Usuario();

                    // Utilizando o método de criação do objeto usuário do padrão de projeto DAO.
                    return $this->usuarioDao->createUsuario($usuario);
                
                case "Buscar usuário":

                   return $usuario = $this->usuarioDao->readUsuario($id);

                case "Cadastrar Arduino":

                    $nome = $dados['nome'];
                    $imagem = $dados['imagem'];
                    $valor = $dados['valor'];
                    $quantidade = $dados['quantidade'];
                    $tipoProduto = $dados['tipoProduto'];
                    $categoria = $dados['categoria'];
                    $descricao = $dados['descricao'];

                    $fabricaArduino = new ArduinoConcreteCreator();
                    $arduino = $fabricaArduino->criarProduto($imagem, $nome, $valor, $quantidade, $categoria, $tipoProduto, $descricao);

                    return $this->produtoDao->createProduto($arduino);

                default:
                    
                    throw new Exception("Operação inválida: " . $tipoOperacao);

            }
            
        }

    }

?>