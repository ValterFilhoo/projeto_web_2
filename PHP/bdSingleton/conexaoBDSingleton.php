<?php 

class ConexaoBDSingleton {

    private $host;
    private $senhaBD;
    private $usuario;
    private $esquema;
    private $porta;
    private $conexao;
    public static $instanciaUnica = null;

    // Construtor da classe, passando os parâmetros das conexões do banco.
    private function __construct($hostBD, $usuarioBD, $senhaBD, $esquemaBD, $portaBD) {
        
        $this -> usuario = $usuarioBD;
        $this -> senhaBD = $senhaBD;
        $this -> esquema = $esquemaBD;
        $this -> porta = $portaBD;
        $this -> host = $hostBD;

        $this -> conexao = new mysqli($this -> host, $this -> usuario, $this -> senhaBD, $this -> esquema, $this -> porta);

        // Verifica se a conexão não houve erro.
        if ($this->conexao->connect_error) {

            die("<br>Ocorreu uma falha na conexão com o banco: " . $this->conexao->connect_error);
            
        }

    }

    public function setUsuario($usuarioBD): void {
        $this -> usuario = $usuarioBD;
    }

    public function setSenha($senhaBD): void {
        $this -> senhaBD = $senhaBD;
    }

    public function setEsquema($esquema): void {
        $this -> esquema = $esquema;
    }

    public function setPorta($porta): void {
        $this -> porta = $porta;
    }

    public function setHost($host): void {
        $this -> host = $host;
    }

    public function getUsuario(): mixed {
        return $this -> usuario;
    }

    public function getSenha(): mixed {
        return $this -> senhaBD;
    }

    public function getEsquema(): mixed {
        return $this -> esquema;
    }

    public function getPorta(): mixed {
        return $this -> porta;
    }

    public function getHost(): mixed {
        return $this -> host;
    }

    public function getConexao(): mysqli {

        return $this->conexao;

    }

    // Método para executar a conexão com o banco.
    public function verificarConexao(): void {
        
        // Verifica se a conexão não houve erro.
        if ($this -> conexao -> connect_error) {

            echo "<br>Ocorreu uma falha na conexão com o banco: ". $this -> conexao -> connect_error;

        } else {

            echo "<br>A conexão com o banco foi realizada com sucesso.";

        };

    }

    // Método para terminar a conexão com o banco de dados.
    public function encerrarConexao(): void {
        
        if ($this -> conexao -> close()) {

            echo "<br>A conexão com o banco foi encerrada com sucesso.";

        } else {
            
            echo "<br>Ocorreu um erro com o encerramento da conexão com o banco de dados.";

        };

    }

    // Método estático para pegar a instância unica da conexão, seguindo o padrão singleton.
    public static function getInstancia($hostBD, $usuarioBD, $senhaBD, $esquemaBD, $portaBD): mixed {
        
        // Pegando a intância unica que é estática, verificando se ela já foi instanciada.
        // utilizando "self", pois em php para ter acesso a atributos "static" só por meio dessa palavra chave.
        if (self::$instanciaUnica == null) {

            // Se não foi instanciada ainda, será inicializada passando os dados da conexão.
            self::$instanciaUnica = new self($hostBD, $usuarioBD, $senhaBD, $esquemaBD, $portaBD);

        };

        return self::$instanciaUnica;

    }

};
