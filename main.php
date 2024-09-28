<?php 

    // Importando o conteúdo do arquivo que contem a classe Singleton da conexão com o banco de dados.
    require '/xampp/htdocs/projeto_web_2/bdSingleton/conexaoBDSingleton.php';

    // Importando o conteúdo do arquivo que contém a classe concreta de criação do produto.
    // para cada produto criado, usando o padrão Factory Method, terá que importar o arquivo de criação especifico.
    require '/xampp/htdocs/projeto_web_2/produtosFactoryMethod/arduinoConcreteCreator.php';

    require '/xampp/htdocs/projeto_web_2/facadeCrud/facadeCrud.php';

    // Informações do banco de dados para fazer a conexão.
    $usuario = "root";
    $senha = "";
    $esquema = "";
    $host = "localhost";
    $porta = 3306;

    // Passando os dados para iniciar a instancia unica do banco de dados, usando o padrão Singleton.
    $bd = ConexaoBDSingleton::getInstancia($host, $usuario, $senha, $esquema, $porta);

    // Executando o método do objeto do banco, no padrão Singleton, para iniciar a conexão com o banco de dados.
    $bd -> verificarConexao();

    $bd ->encerrarConexao();

    // Instanciando o objeto da fábrica concreta (ConcreteCreator), que será responsável por criar o produto especifico (Product Concrect).
    $fabricaArduino = new ArduinoConcreteCreator();

    // Instanciando um objeto crud do tipo facade, onde terão todas as funcionalidades de crud, seja para produtos, usuários, pedidos etc.
    $crud = new CRUDFacade();

    // Exemplos do uso do facade.
    //$crud->cadastrarProduto();
    //$crud->cadastrarUsuario();

    // Pegando o objeto criado pela fábrica de Arduino, pois ela contém um método para criar o produto (Factory Method) que retorna o produto concreto que foi criado.
    $sensorArduino = $fabricaArduino->criarProduto("Imagem.png", "Sensor de temperatura do Arduino", 300, 10, "Arduino", "Sensor", "Esse é um sensor de temperatura.");

    $placaArduino = $fabricaArduino->criarProduto("Imagem.png", "Placa de Arduino", 200, 10, "Arduino", "Placa", "Essa é uma placa.");

    echo '<br>';
    
    // Para testes, utilizando os métodos getters para acessar as informações do produto.
    echo '<br> Nome: ' . $sensorArduino->getNome();
    echo '<br> Imagem: ' . $sensorArduino->getImagem();
    echo '<br> Valor: ' . $sensorArduino->getValor();
    echo '<br> Quantidade: ' . $sensorArduino->getQuantidade();
    echo '<br> Categoria: ' . $sensorArduino->getCategoria();
    echo '<br> Tipo: ' . $sensorArduino->getTipo();
    echo '<br> Descrição: '. $sensorArduino->getDescricao();

    echo '<br>';

    echo '<br> Nome: ' . $placaArduino->getNome();
    echo '<br> Imagem: ' . $placaArduino->getImagem();
    echo '<br> Valor: ' . $placaArduino->getValor();
    echo '<br> Quantidade: ' . $placaArduino->getQuantidade();
    echo '<br> Categoria: ' . $placaArduino->getCategoria();
    echo '<br> Tipo: ' . $placaArduino->getTipo();
    echo '<br> Descrição: '. $placaArduino->getDescricao();



?>