<?php 

    class CrudUsuarioDaoImplementacao implements CrudUsuarioDao{

        private $conexaoBD;

        public function __construct() {

            $this->conexaoBD = ConexaoBDSingleton::getInstancia("localhost", "root", "96029958va", "panorama_catuense", 3306)->getConexao();

        }

        public function createUsuario($usuario) {

            $sql = "INSERT INTO usuario(nome, cpf, celular, telefone, sexo, dataNascimento, cep, endereco, numero, complemento, referencia, bairro, cidade, estado, tipo)
            VALUES ('$usuario->nome', '$usuario->cpf', '$usuario->celular', '$usuario->telefone', '$usuario->sexo', '$usuario->dataNascimento', '$usuario->cep', '$usuario->endereco), '$usuario->numero', '$usuario->complemento', '$usuario->referencia', '$usuario->bairro', '$usuario->cidade', '$usuario->estado', 'Usuário'";

            $resultadoCriarUsuario = $this->conexaoBD->query($sql);

            if ($resultadoCriarUsuario) {

                echo "Usuário cadastrado com sucesso.";

                return true;

            } else {

                echo "Erro ao cadastrar usuário: " . $this->conexaoBD->error;

                return false;

            }


        }

        public function readUsuario($idUsuario) {

            $sql = "SELECT * FROM usuario WHERE id = $idUsuario";

            $resultadoDaBusca = $this->conexaoBD->query($sql);

            if ($resultadoDaBusca->num_rows > 0) {

                $usuarioEncontrado = $resultadoDaBusca->fetch_assoc();

                echo "Usuario encontrado.";

                return $usuarioEncontrado;

            } else {

                echo 'Usuario não encontrado.';

                return null;

            }

        }

        public function readUsuarios(){
            
            $sql = "SELECT * FROM usuario";

            $resultadoDaBusca = $this->conexaoBD->query($sql);

            if ($resultadoDaBusca->num_rows > 0) {

                $usuariosEncontrados = $resultadoDaBusca->fetch_assoc();

                echo "Usuários encontrados.";

                return $usuariosEncontrados;

            } else {

                echo 'Nenhum usuário encontrado.';
                
                return null;

            }

        }
    
        public function updateUsuario($idUsuario, $usuario) {

            $sql = "UPDATE usuario SET WHERE id = $idUsuario";

            $resultadoEdicao = $this->conexaoBD->query($sql);

            if ($resultadoEdicao) {
                
                echo 'Usuário editado com sucesso.';

                return true;


            } else {

                echo 'Erro na edição do usuário: ' . $this->conexaoBD->error;

                return false;

            }

        }
    
        public function deleteUsuario($idUsuario){

            $sql = "DELETE FROM usuario WHERE id = $idUsuario";

            $resultadoExclusao = $this->conexaoBD->query($sql);

            if ($resultadoExclusao) {
                
                echo 'Usuário excluido com sucesso.';

                return true;


            } else {

                echo 'Erro na exclusão do usuário: ' . $this->conexaoBD->error;

                return false;

            }


        }

    }

?>