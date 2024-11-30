<?php 

class ConexaoBDSingleton {

    private string $host;
    private string $senhaBD;
    private string $usuario;
    private string $esquema;
    private int $porta;
    private mysqli $conexao;
    public static ?ConexaoBDSingleton $instanciaUnica = null;

    // Construtor da classe, passando os parâmetros das conexões do banco.
    private function __construct(string $hostBD, string $usuarioBD, string $senhaBD, string $esquemaBD, int $portaBD) {
        
        $this->usuario = $usuarioBD;
        $this->senhaBD = $senhaBD;
        $this->esquema = $esquemaBD;
        $this->porta = $portaBD;
        $this->host = $hostBD;

        $this->conexao = new mysqli($this->host, $this->usuario, $this->senhaBD, $this->esquema, $this->porta);

        // Verifica se a conexão não houve erro.
        if ($this->conexao->connect_error) {
            die("<br>Ocorreu uma falha na conexão com o banco: " . $this->conexao->connect_error);
        }
    }

    public function setUsuario(string $usuarioBD): void {
        $this->usuario = $usuarioBD;
    }

    public function setSenha(string $senhaBD): void {
        $this->senhaBD = $senhaBD;
    }

    public function setEsquema(string $esquemaBD): void {
        $this->esquema = $esquemaBD;
    }

    public function setPorta(int $portaBD): void {
        $this->porta = $portaBD;
    }

    public function setHost(string $hostBD): void {
        $this->host = $hostBD;
    }

    public function getUsuario(): string {
        return $this->usuario;
    }

    public function getSenha(): string {
        return $this->senhaBD;
    }

    public function getEsquema(): string {
        return $this->esquema;
    }

    public function getPorta(): int {
        return $this->porta;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function getConexao(): mysqli {
        return $this->conexao;
    }

    // Método para executar a conexão com o banco.
    public function verificarConexao(): void {
        
        // Verifica se a conexão não houve erro.
        if ($this->conexao->connect_error) {
            echo "<br>Ocorreu uma falha na conexão com o banco: " . $this->conexao->connect_error;
        } else {
            echo "<br>A conexão com o banco foi realizada com sucesso.";
        }
        
    }

    // Método para terminar a conexão com o banco de dados.
    public function encerrarConexao(): void {

        if ($this->conexao->close()) {
            echo "<br>A conexão com o banco foi encerrada com sucesso.";
        } else {
            echo "<br>Ocorreu um erro com o encerramento da conexão com o banco de dados.";
        }

    }

    // Método estático para pegar a instância unica da conexão, seguindo o padrão singleton.
    public static function getInstancia(string $hostBD, string $usuarioBD, string $senhaBD, string $esquemaBD, int $portaBD): ?ConexaoBDSingleton {

        // Pegando a instância unica que é estática, verificando se ela já foi instanciada.
        // utilizando "self", pois em PHP para ter acesso a atributos "static" só por meio dessa palavra chave.
        if (self::$instanciaUnica === null) {
            // Se não foi instanciada ainda, será inicializada passando os dados da conexão.
            self::$instanciaUnica = new self($hostBD, $usuarioBD, $senhaBD, $esquemaBD, $portaBD);
        }
        return self::$instanciaUnica;
    }

}
