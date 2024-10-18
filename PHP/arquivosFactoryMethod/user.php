<?php 

// Classe abstrata em vez da interface, do padrão FactoryMethod, pois existem atributos em comum entre todos os produtos do nosso site.
abstract class User {


    protected int $id = -1;
    protected string $nomeCompleto;
    protected string $email;
    protected string $cpf;
    protected string $celular;
    protected string $sexo;
    protected string $senha;
    protected string $dataNascimento;
    protected string $cep;
    protected string $endereco;
    protected int $numeroEndereco;
    protected string $complemento;
    protected string $referencia;
    protected string $bairro;
    protected string $cidade;
    protected string $estado;
    protected string $tipoConta = "cliente";

    public function __construct(string $nomeCompleto, string $email, string $cpf, string $celular, string $sexo, string $senha, string $dataNascimento, string $cep, string $endereco, int $numeroEndereco, string $complemento, string $referencia, string $bairro, $cidade, string $estado) {

        $this->nomeCompleto = $nomeCompleto;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->celular = $celular;
        $this->sexo = $sexo;
        $this->senha = $senha;
        $this->dataNascimento = $dataNascimento;
        $this->cep = $cep;
        $this->endereco = $endereco;
        $this->numeroEndereco = $numeroEndereco;
        $this->complemento = $complemento;
        $this->referencia = $referencia;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;

    }

    // Getters e Setters
    public function getId(): string {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNomeCompleto(): string {
        return $this->nomeCompleto;
    }

    public function setNomeCompleto(string $nomeCompleto): void {
        $this->nomeCompleto = $nomeCompleto;
    }
    
    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getCpf(): string { 
        return $this->cpf;
    }
    public function setCpf(string $cpf): void { 
        $this->cpf = $cpf;
    }
    public function getCelular(): string {
        return $this->celular;
    }
    public function setCelular(string $celular): void { 
        $this->celular = $celular;
    }
    public function getSexo(): string { 
        return $this->sexo;
    }
    public function setSexo(string $sexo): void { 
        $this->sexo = $sexo;
    }
    public function getSenha(): string {
        return $this->senha;
    }
    public function setSenha(string $senha): void { 
        $this->senha = $senha;
    }
    public function getDataNascimento(): string { 
        return $this->dataNascimento;
    }
    public function setDataNascimento(string $dataNascimento): void { 
        $this->dataNascimento = $dataNascimento;
    }
    public function getCep(): string { 
        return $this->cep;
    }
    public function setCep(string $cep): void {
        $this->cep = $cep;
    }
    public function getEndereco(): string { 
        return $this->endereco;
    }
    public function setEndereco(string $endereco): void {  
        $this->endereco = $endereco;
    }
    public function getNumeroEndereco(): string { 
        return $this->endereco;
    }
    public function setNumeroEndereco(int $numeroEndereco): void { 
        $this->numeroEndereco = $numeroEndereco;
    }
    public function getComplemento(): string { 
        return $this->complemento;
    }
    public function setComplemento(string $complemento): void { 
        $this->complemento = $complemento;
    }
    public function getReferencia(): string { 
        return $this->referencia; 
    }
    public function setReferencia(string $referencia): void { 
        $this->referencia = $referencia;
    }
    public function getBairro(): string { 
        return $this->bairro;
     }
    public function setBairro(string $bairro): void {  
        $this->bairro = $bairro;
    }
    public function getCidade(): string {  
        return $this->cidade;
    }
    public function setCidade(string $cidade) {  
        $this->cidade = $cidade;
    }
    public function getEstado(): string { 
        return $this->estado;
    }
    public function setEstado(string $estado): void { 
        $this->estado = $estado;
    }
    public function getTipoConta(): string { 
        return $this->tipoConta;
    }
    public function setTipoConta(string $tipoConta): void {  
        $this->tipoConta = $tipoConta;
    }


}

?>