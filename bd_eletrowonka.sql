CREATE DATABASE eletrowonka;

USE eletrowonka;


CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeCompleto VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    celular VARCHAR(15) NOT NULL,
    sexo VARCHAR(9) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    dataNascimento DATE NOT NULL,
    cep VARCHAR(8) NOT NULL,
    endereco VARCHAR(100) NOT NULL,
    numeroEndereco VARCHAR(10) NOT NULL,
    complemento VARCHAR(50) NOT NULL,
    referencia VARCHAR(100) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    estado CHAR(2) NOT NULL,
    tipoConta ENUM('Cliente', 'Admin') NOT NULL
);

CREATE TABLE produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagemProduto VARCHAR(255) NOT NULL,
    nomeProduto VARCHAR(100) NOT NULL,
    valorProduto DECIMAL(10, 2) NOT NULL,
    quantidade INT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    tipoProduto VARCHAR(50) NOT NULL,
    descricaoProduto TEXT NOT NULL,
    produtosKit TEXT -- Adicionado para armazenar os produtos do kit, se houver
);

CREATE TABLE pedido ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    idUsuario INT NOT NULL, 
    dataPedido DATETIME NOT NULL, 
    tipoPagamento ENUM('cartao_credito', 'pix', 'boleto') NOT NULL, 
    chavePix VARCHAR(50), 
    numeroCartao VARCHAR(20), 
    quantidadeParcelas INT, 
    numeroBoleto VARCHAR(50), 
    valor DECIMAL(10, 2) NOT NULL, 
    valorParcelas DECIMAL(10, 2), -- Nova coluna para armazenar o valor das parcelas
    FOREIGN KEY (idUsuario) REFERENCES usuario(id)
);

CREATE TABLE pedido_produto (
    idPedido INT NOT NULL,
    idProduto INT NOT NULL,
    quantidade INT NOT NULL,
    valorItem DECIMAL(10, 2) NOT NULL,
    produtosKit TEXT,
    PRIMARY KEY (idPedido, idProduto),
    FOREIGN KEY (idPedido) REFERENCES pedido(id),
    FOREIGN KEY (idProduto) REFERENCES produto(id)
);


INSERT INTO usuario (
    nomeCompleto, cpf, celular, sexo, email, senha, dataNascimento, cep, endereco, numeroEndereco, complemento, referencia, bairro, cidade, estado, tipoConta
) VALUES (
    'Admin', '12345678901', '999999999', 'Masculino', 'admin@gmail.com', '12345678', '1980-01-01', '12345678', 'Rua Exemplo', '123', 'Apto 1', 'Perto da praça', 'Centro', 'Cidade Exemplo', 'EX', 'Admin'
);

