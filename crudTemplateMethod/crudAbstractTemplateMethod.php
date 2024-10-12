<?php

    abstract class CrudTemplateMethod {

        private $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia("localhost", "root", "96029958va", "panorama_catuense", 3306)->getConexao();

        }

        public function createEntidade($entidade) {
            
            // Parte do código que varia entre as subclasses de CRUD das entidades produto, pedido e usuario.
            $sql = $this->sqlCreate($entidade);


            $resultadoInsert = $this->conexaoBD->query($sql);

            // Verificando se realmente cadastrou o produto no banco.
            // Vai retornar true se a query deu certo.
            if ($resultadoInsert) {

                echo "Cadastro realizado com sucesso.";

                return true;


            } else {


                echo "Erro no cadastro: " . $this->conexaoBD->error;

                return false;

            }

        }

        public function readEntidade($id) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlRead($id);
            
            $resultadoDaBusca = $this->conexaoBD->query($sql);

            if ($resultadoDaBusca->num_rows > 0) {

                $entidadeEncontrada = $resultadoDaBusca->fetch_assoc();

                echo "Entidade encontrada.";

                return $entidadeEncontrada;

            } else {

                echo 'Entidade não encontrada.';

                return null;

            }

        }

        public function updateEntidade($id, $entidade) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlUpdate($id, $entidade);

            $resultadoEdicao = $this->conexaoBD->query($sql);

            if ($resultadoEdicao) {
                
                echo 'Entidade editada com sucesso.';

                return true;


            } else {

                echo 'Erro na edição da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }

        public function deleteEntidade($id) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlDelete($id);

            $resultadoExclusao = $this->conexaoBD->query($sql);

            if ($resultadoExclusao) {
                
                echo 'Entidade excluida com sucesso.';

                return true;


            } else {

                echo 'Erro na exclusão da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }


        abstract public function sqlCreate($entidade);

        abstract public function sqlRead($id);

        abstract public function sqlUpdate($id, $entidade);

        abstract public function sqlDelete($id);

    }

?>
