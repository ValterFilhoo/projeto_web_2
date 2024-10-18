<?php

    require_once __DIR__ . "/../bdSingleton/conexaoBDSingleton.php";
    require_once __DIR__ . "/../bdSingleton/configConexao.php";

    abstract class CrudTemplateMethod {

        protected $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_ESCHEMA, BD_PORTA)->getConexao();

        }

        public function createEntidade($entidade) {
        
            // Pegando a parte do método que varia entre as subclasses, nesse caso a string do Insert.
            $sql = $this->sqlCreate();
            
            // Usando o método de preparação da declaração da operação que será feita.
            $stmt = $this->conexaoBD->prepare($sql);

            // Binding dos parâmetros.
            $this->bindParams($stmt, $entidade);
            
            // Pegando o resultado da inserção no banco de dados.
            $resultadoInsert = $stmt->execute();

            // Verificando se realmente cadastrou a entidade no banco.
            // Vai retornar true se a query deu certo.
            if ($resultadoInsert) {

                return true;

            } else {

                echo "Erro no cadastro: " . $stmt->error;
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


        abstract public function sqlCreate();

        abstract public function sqlRead($id);

        abstract public function sqlUpdate($id, $entidade);

        abstract public function sqlDelete($id);

        abstract protected function bindParams($stmt, $entidade);


    }

?>
