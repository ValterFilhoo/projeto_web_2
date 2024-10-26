<?php

    require_once __DIR__ . "/../bdSingleton/conexaoBDSingleton.php";
    require_once __DIR__ . "/../bdSingleton/configConexao.php";

    abstract class CrudTemplateMethod {

        protected $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_ESCHEMA, BD_PORTA)->getConexao();

        }

        public function criarEntidade($entidade) {
            
            $operacao = "Criar";
            
            // Pegando a parte do método que varia entre as subclasses, nesse caso a string do Insert.
            $sql = $this->sqlCriar();
            
            // Usando o método de preparação da declaração da operação que será feita.
            $stmt = $this->conexaoBD->prepare($sql);

            // Vinculação dos parâmetros dos valores que serão inseridos na tabela do banco de dados.
            $this->vincularParametros($stmt, $entidade, $operacao);
            
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

        public function lerEntidade($id) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlLer($id);
            
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

        public function atualizarEntidade($id, $entidade) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlAtualizar($id, $entidade);

            $resultadoEdicao = $this->conexaoBD->query($sql);

            if ($resultadoEdicao) {
                
                echo 'Entidade editada com sucesso.';

                return true;


            } else {

                echo 'Erro na edição da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }

        public function deletarEntidade($id) {

            // Parte do código que variam entre as subclasses.
            $sql = $this->sqlDeletar($id);

            $resultadoExclusao = $this->conexaoBD->query($sql);

            if ($resultadoExclusao) {
                
                echo 'Entidade excluida com sucesso.';

                return true;


            } else {

                echo 'Erro na exclusão da entidade: ' . $this->conexaoBD->error;

                return false;

            }

        }

        public function listarEntidades() {

        }

        abstract public function sqlCriar(): string;

        abstract public function sqlLer(): string;

        abstract public function sqlAtualizar(): string;

        abstract public function sqlDeletar(): string;

        abstract public function sqlListar(): string;

        abstract public function vincularParametros($declaracao, $entidade, $operacao): void;


    }

?>
