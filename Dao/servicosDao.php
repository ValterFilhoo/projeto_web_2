<?php 

    class ServicosDao {

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

                    $fabricaArduino = new ArduinoConcreteCreator();
                    $fabricaArduino->criarProduto();

                    return $this->produtoDao->createProduto($fabricaArduino);

                default:
                    
                    throw new Exception("Operação inválida: " . $tipoOperacao);

            }
            
        }

    }

?>