<?php 

    // Importando os arquivos das classes utilizadas aqui.
    require_once __DIR__ . "/../userCreator.php";
    require_once __DIR__ . "/../productUser/userClienteConcrete.php";
    require_once __DIR__ . "/../productUser/userAdminConcrete.php";

    // A classe concreta da Fábrica de Arduino, herdando o comportamento da fábrica abstrata (Creator).
    class UserConcreteCreator extends UserCreator {

        // Implementando o método do padrão de projeto para criação do produto especifico dessa fábrica.
        public function retornarInstanciaUsuario(string $nomeCompleto, 
        string $email, 
        string $cpf, 
        string $celular, 
        string $sexo, 
        string $senha, 
        string $dataNascimento, 
        string $cep, 
        string $endereco, 
        int $numeroEndereco, 
        string $complemento, 
        string $referencia, 
        string $bairro, 
        string $cidade, 
        string $estado, 
        string $tipoConta): User {
            
        switch ($tipoConta) {

                case 'Cliente':  
                    
                    // Retornando a instância do produto instanciado.
                    return new UserClienteConcrete($nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado, $tipoConta);

                case 'Admin':

                    return new UserAdminConcrete($nomeCompleto, $email, $cpf, $celular, $sexo, $senha, $dataNascimento, $cep, $endereco, $numeroEndereco, $complemento, $referencia, $bairro, $cidade, $estado, $tipoConta);
                    

                // Conforme for inserindo mais produtos concretos de Arduino futuramente, será acrescentado aqui a instancia desse produto.

                default: // Se o tipo do produto não for igual a um dos casos acima.

                throw new Exception("Erro. Tipo de usuário inválido.");
                
                
            }
        
            
        }

}