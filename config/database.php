<?php
class Database {
    private $sqlite_file = 'database/eventos.db';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Criar diretório se não existir
            $dir = dirname($this->sqlite_file);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            
            // Conexão SQLite
            $this->conn = new PDO("sqlite:" . $this->sqlite_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Habilitar chaves estrangeiras no SQLite
            $this->conn->exec("PRAGMA foreign_keys = ON");
            
            // Criar tabelas se não existirem
            $this->createTablesIfNotExists();
            
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }
    
    private function createTablesIfNotExists() {
        try {
            // Verificar se as tabelas já existem
            $result = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='Evento'");
            if ($result->fetchColumn()) {
                return; // Tabelas já existem
            }
            
            // Scripts SQL para SQLite
            $sqlScripts = [
                "CREATE TABLE Categoria (
                    id_categoria INTEGER PRIMARY KEY AUTOINCREMENT,
                    tipo VARCHAR(50) NOT NULL,
                    nome VARCHAR(100) NOT NULL UNIQUE,
                    descricao VARCHAR(255)
                )",
                
                "CREATE TABLE Local (
                    id_local INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    campus VARCHAR(100) NOT NULL,
                    sala VARCHAR(50) NOT NULL,
                    capacidade INTEGER NOT NULL
                )",
                
                "CREATE TABLE Participante (
                    id_participante INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    matricula VARCHAR(20) NOT NULL UNIQUE,
                    curso VARCHAR(100) NOT NULL,
                    data_inscricao DATE NOT NULL
                )",
                
                "CREATE TABLE Organizador (
                    id_organizador INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    telefone VARCHAR(20) NOT NULL,
                    vinculo VARCHAR(50) NOT NULL
                )",
                
                "CREATE TABLE Palestrante (
                    id_palestrante INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    instituicao VARCHAR(100) NOT NULL
                )",
                
                "CREATE TABLE Evento (
                    id_evento INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    descricao VARCHAR(255),
                    data_inicio DATE NOT NULL,
                    data_fim DATE NOT NULL,
                    id_categoria INTEGER NOT NULL,
                    id_local INTEGER NOT NULL,
                    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
                    FOREIGN KEY (id_local) REFERENCES Local(id_local)
                )",
                
                "CREATE TABLE Atividade (
                    id_atividade INTEGER PRIMARY KEY AUTOINCREMENT,
                    titulo VARCHAR(100) NOT NULL,
                    tipo VARCHAR(50) NOT NULL,
                    id_evento INTEGER NOT NULL,
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
                )",
                
                "CREATE TABLE Inscricao (
                    id_participante INTEGER NOT NULL,
                    id_evento INTEGER NOT NULL,
                    status VARCHAR(50) NOT NULL,
                    data_inscricao DATE NOT NULL,
                    PRIMARY KEY (id_participante, id_evento),
                    FOREIGN KEY (id_participante) REFERENCES Participante(id_participante),
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
                )",
                
                "CREATE TABLE Certificado (
                    id_certificado INTEGER PRIMARY KEY AUTOINCREMENT,
                    data_emissao DATE NOT NULL,
                    carga_horaria REAL NOT NULL,
                    id_participante INTEGER NOT NULL,
                    id_evento INTEGER NOT NULL,
                    FOREIGN KEY (id_participante) REFERENCES Participante(id_participante),
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
                )",
                
                "CREATE TABLE Feedback (
                    id_feedback INTEGER PRIMARY KEY AUTOINCREMENT,
                    nota INTEGER NOT NULL CHECK (nota >= 0 AND nota <= 10),
                    comentario VARCHAR(255),
                    id_participante INTEGER NOT NULL,
                    id_evento INTEGER NOT NULL,
                    FOREIGN KEY (id_participante) REFERENCES Participante(id_participante),
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
                )",
                
                "CREATE TABLE Organiza_Evento (
                    id_organizador INTEGER NOT NULL,
                    id_evento INTEGER NOT NULL,
                    PRIMARY KEY (id_organizador, id_evento),
                    FOREIGN KEY (id_organizador) REFERENCES Organizador(id_organizador),
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento)
                )",
                
                "CREATE TABLE Premiacao (
                    id_evento INTEGER NOT NULL,
                    id_participante INTEGER NOT NULL,
                    colocacao INTEGER NOT NULL,
                    premio REAL NOT NULL,
                    PRIMARY KEY (id_evento, id_participante),
                    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento),
                    FOREIGN KEY (id_participante) REFERENCES Participante(id_participante)
                )",
                
                "CREATE TABLE Atividade_Palestrante (
                    id_atividade INTEGER NOT NULL,
                    id_palestrante INTEGER NOT NULL,
                    PRIMARY KEY (id_atividade, id_palestrante),
                    FOREIGN KEY (id_atividade) REFERENCES Atividade(id_atividade),
                    FOREIGN KEY (id_palestrante) REFERENCES Palestrante(id_palestrante)
                )"
            ];
            
            // Executar scripts de criação
            foreach ($sqlScripts as $sql) {
                $this->conn->exec($sql);
            }
            
            // Inserir dados iniciais
            $this->insertInitialData();
            
        } catch(PDOException $e) {
            echo "Erro ao criar tabelas: " . $e->getMessage();
        }
    }
    
    private function insertInitialData() {
        try {
            // Inserir categorias básicas
            $this->conn->exec("INSERT INTO Categoria (tipo, nome, descricao) VALUES
                ('Acadêmico', 'Conferência', 'Eventos acadêmicos e conferências'),
                ('Técnico', 'Workshop', 'Workshops técnicos e práticos'),
                ('Social', 'Networking', 'Eventos de networking e socialização'),
                ('Competição', 'Hackathon', 'Competições e hackathons'),
                ('Cultural', 'Palestra', 'Eventos culturais e palestras')");
            
            // Inserir locais básicos
            $this->conn->exec("INSERT INTO Local (nome, campus, sala, capacidade) VALUES
                ('Auditório Central', 'Campus Principal', 'Auditório 1', 200),
                ('Laboratório de Informática', 'Campus Principal', 'Lab 101', 50),
                ('Sala de Conferências', 'Campus Principal', 'Conf 201', 100),
                ('Biblioteca Central', 'Campus Principal', 'Sala de Estudos', 80),
                ('Centro de Convenções', 'Campus Sul', 'Salão Principal', 500)");
            
            // Inserir participantes de exemplo
            $this->conn->exec("INSERT INTO Participante (nome, email, matricula, curso, data_inscricao) VALUES
                ('João Silva', 'joao.silva@email.com', '2021001', 'Ciência da Computação', '2024-01-15'),
                ('Maria Santos', 'maria.santos@email.com', '2021002', 'Engenharia de Software', '2024-01-16'),
                ('Pedro Oliveira', 'pedro.oliveira@email.com', '2021003', 'Sistemas de Informação', '2024-01-17'),
                ('Ana Costa', 'ana.costa@email.com', '2021004', 'Análise e Desenvolvimento de Sistemas', '2024-01-18'),
                ('Carlos Mendes', 'carlos.mendes@email.com', '2021005', 'Engenharia da Computação', '2024-01-19')");
            
            // Inserir organizadores de exemplo
            $this->conn->exec("INSERT INTO Organizador (nome, email, telefone, vinculo) VALUES
                ('Prof. Dr. Anderson Silva', 'anderson.silva@univ.edu', '(11) 99999-0001', 'Professor'),
                ('Prof. Dra. Mariana Costa', 'mariana.costa@univ.edu', '(11) 99999-0002', 'Professora'),
                ('Coordenação de TI', 'coord.ti@univ.edu', '(11) 99999-0003', 'Coordenação')");
            
            // Inserir palestrantes de exemplo
            $this->conn->exec("INSERT INTO Palestrante (nome, email, instituicao) VALUES
                ('Dr. Paulo Roberto', 'paulo.roberto@tech.com', 'TechCorp'),
                ('Dra. Sandra Alves', 'sandra.alves@innovation.com', 'Innovation Labs'),
                ('Eng. Miguel Torres', 'miguel.torres@startup.com', 'StartupTech')");
            
        } catch(PDOException $e) {
            // Não é erro crítico se não conseguir inserir dados iniciais
            error_log("Aviso: Não foi possível inserir dados iniciais: " . $e->getMessage());
        }
    }
}
?>
