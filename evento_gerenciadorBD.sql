
CREATE DATABASE IF NOT EXISTS eventos_gerenciador;
USE eventos_gerenciador;

CREATE TABLE Local (
    id_local INT PRIMARY KEY AUTO_INCREMENT,
    nome_local VARCHAR(100) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    capacidade INT NOT NULL
);

CREATE TABLE Organizador (
    id_organizador INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20)
);

CREATE TABLE Evento (
    id_evento INT PRIMARY KEY AUTO_INCREMENT,
    nome_evento VARCHAR(100) NOT NULL,
    data_evento DATETIME NOT NULL,
    id_local INT,
    id_organizador INT,
    FOREIGN KEY (id_local) REFERENCES Local(id_local),
    FOREIGN KEY (id_organizador) REFERENCES Organizador(id_organizador)
);

CREATE TABLE Participante (
    id_participante INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20)
);

CREATE TABLE Ingresso (
    id_ingresso INT PRIMARY KEY AUTO_INCREMENT,
    id_evento INT,
    tipo VARCHAR(50) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
);

CREATE TABLE Participante_Evento (
    id_participante INT,
    id_evento INT,
    data_inscricao DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_participante, id_evento),
    FOREIGN KEY (id_participante) REFERENCES Participante(id_participante),
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
);

INSERT INTO Local (nome_local, endereco, capacidade) VALUES
('Centro de Convenções', 'Av. Principal, 123, São Paulo', 500),
('Auditório Municipal', 'Rua Secundária, 456, Rio de Janeiro', 200),
('Espaço Cultural', 'Praça Central, 789, Belo Horizonte', 300);

INSERT INTO Organizador (nome, email, telefone) VALUES
('João Silva', 'joao.silva@email.com', '(11) 99999-1111'),
('Maria Oliveira', 'maria.oliveira@email.com', '(21) 98888-2222'),
('Pedro Santos', 'pedro.santos@email.com', '(31) 97777-3333');

INSERT INTO Evento (nome_evento, data_evento, id_local, id_organizador) VALUES
('Conferência Tech 2025', '2025-11-10 09:00:00', 1, 1),
('Show de Rock', '2025-12-15 20:00:00', 2, 2),
('Feira Cultural', '2025-10-20 10:00:00', 3, 3);

INSERT INTO Participante (nome, email, telefone) VALUES
('Ana Costa', 'ana.costa@email.com', '(11) 96666-4444'),
('Bruno Lima', 'bruno.lima@email.com', '(21) 95555-5555'),
('Clara Mendes', 'clara.mendes@email.com', '(31) 94444-6666'),
('Diego Almeida', 'diego.almeida@email.com', '(11) 93333-7777');

INSERT INTO Ingresso (id_evento, tipo, preco) VALUES
(1, 'VIP', 150.00),
(1, 'Padrão', 50.00),
(2, 'Pista', 80.00),
(2, 'Camarote', 200.00),
(3, 'Entrada Única', 30.00);

INSERT INTO Participante_Evento (id_participante, id_evento, data_inscricao, status) VALUES
(1, 1, '2025-10-01 14:30:00', 'Confirmado'),
(2, 1, '2025-10-02 09:15:00', 'Confirmado'),
(3, 2, '2025-10-05 16:00:00', 'Pendente'),
(4, 3, '2025-10-07 11:45:00', 'Confirmado');

CREATE VIEW vw_eventos_detalhes AS
SELECT 
    e.id_evento, 
    e.nome_evento, 
    e.data_evento, 
    l.nome_local, 
    o.nome AS nome_organizador
FROM Evento e
JOIN Local l ON e.id_local = l.id_local
JOIN Organizador o ON e.id_organizador = o.id_organizador;

SELECT * FROM vw_eventos_detalhes;

SELECT 
    e.nome_evento, 
    COUNT(pe.id_participante) AS total_participantes, 
    SUM(i.preco) AS receita_total, 
    AVG(i.preco) AS preco_medio_ingresso
FROM Evento e
LEFT JOIN Participante_Evento pe ON e.id_evento = pe.id_evento
LEFT JOIN Ingresso i ON e.id_evento = i.id_evento
GROUP BY e.id_evento, e.nome_evento
HAVING total_participantes > 0;