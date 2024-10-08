<?php 

    class FacadeCrud {

        private $usuarioDao;
        private $produtoDao;

        public function __construct() {

            $this->usuarioDao = new CrudUsuarioDaoImplementacao();
            $this->produtoDao = new CrudProdutoDaoImplementacao();
            
        }


        public function createEntidade($tipoEntidade, $dadosEntidade) {

            switch ($tipoEntidade) {

                case "usuario": 

                    // Instanciar um objeto usuário.
                    //$usuario = new Usuario();
                    // Passar os dados da entidade para o objeto antes de usar o método de create.
                    //$usuario->setEmail($dadosEntidade['email']);

                    // Utilizando o método de criação do objeto usuário do padrão de projeto DAO.
                    // return $this->usuarioDao->createUsuario($usuario);
                
                case "arduino":
                    
                    
                    $fabricaArduino = new ArduinoConcreteCreator();

                    $arduino = $fabricaArduino->criarProduto($dadosEntidade['imagem'], $dadosEntidade['nome'], $dadosEntidade['valor'], $dadosEntidade['quantidade'], $dadosEntidade['categoria'], $dadosEntidade['tipo'], $dadosEntidade['categoria']);

                    return $this->produtoDao->createProduto($arduino);
                
                default:
                    
                    throw new Exception("Entidade inválida: " . $tipoEntidade);
                    
            }

        }

    }

?>