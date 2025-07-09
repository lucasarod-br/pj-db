-- Categoria
INSERT INTO Categoria (tipo, nome, descricao) VALUES
('Acadêmica', 'Seminário', 'Seminários acadêmicos da UnB'),
('Cultural', 'Festival de Música', 'Festival cultural com bandas da UnB'),
('Tecnológica', 'Hackathon', 'Competição tecnológica UnB'),
('Esportiva', 'Campeonato', 'Competições esportivas intercampi'),
('Científica', 'Congresso', 'Congresso científico da UnB');

-- Local
INSERT INTO Local (nome, campus, sala, capacidade) VALUES
('Auditório Central', 'Asa Norte', 'A1', 300),
('Sala Multiuso', 'Asa Sul', 'B2', 120),
('Laboratório de TI', 'Gama', 'C3', 40),
('Ginásio', 'Ceilândia', 'D1', 500),
('Teatro Universitário', 'Planaltina', 'E2', 200);

-- Evento
INSERT INTO Evento (nome, descricao, data_inicio, data_fim, id_categoria, id_local) VALUES
('Seminário de Inovação', 'Discussões sobre inovação na UnB', '2025-08-10', '2025-08-12', 1, 1),
('Festival de Música UnB', 'Bandas da UnB se apresentam.', '2025-09-15', '2025-09-17', 2, 5),
('Hackathon UnB', 'Desafio de programação e inovação.', '2025-10-05', '2025-10-06', 3, 3),
('Campeonato Intercampi', 'Disputas esportivas entre campi.', '2025-11-20', '2025-11-22', 4, 4),
('Congresso Científico UnB', 'Palestras e apresentações científicas.', '2025-12-01', '2025-12-03', 5, 2);

-- Organizador
INSERT INTO Organizador (nome, email, telefone, vinculo) VALUES
('Ana Souza', 'ana.souza@unb.br', '61999990001', 'Professor'),
('Carlos Lima', 'carlos.lima@unb.br', '61999990002', 'Servidor'),
('Fernanda Rocha', 'fernanda.rocha@unb.br', '61999990003', 'Professor'),
('Bruno Alves', 'bruno.alves@unb.br', '61999990004', 'Aluno'),
('Julia Martins', 'julia.martins@unb.br', '61999990005', 'Professor');

-- Participante
INSERT INTO Participante (nome, email, matricula, curso, data_inscricao) VALUES
('João Pereira', 'joao.pereira@aluno.unb.br', '20230001', 'Engenharia', '2025-07-10'),
('Maria Santos', 'maria.santos@aluno.unb.br', '20230002', 'Medicina', '2025-07-11'),
('Pedro Lima', 'pedro.lima@aluno.unb.br', '20230003', 'Direito', '2025-07-12'),
('Larissa Costa', 'larissa.costa@aluno.unb.br', '20230004', 'Computação', '2025-07-13'),
('Lucas Almeida', 'lucas.almeida@aluno.unb.br', '20230005', 'Administração', '2025-07-14');

-- Palestrante
INSERT INTO Palestrante (nome, email, instituicao) VALUES
('Dr. Ricardo Silva', 'ricardo.silva@unb.br', 'UnB'),
('Dra. Paula Menezes', 'paula.menezes@unb.br', 'UnB'),
('Prof. Diego Martins', 'diego.martins@unb.br', 'UnB'),
('Eng. Cláudia Lopes', 'claudia.lopes@unb.br', 'UnB'),
('Prof. Marcos Nunes', 'marcos.nunes@unb.br', 'UnB');

-- Atividade
INSERT INTO Atividade (titulo, tipo, id_evento) VALUES
('Palestra sobre Startups', 'Palestra', 1),
('Show Banda UnB', 'Musical', 2),
('Maratona de Código', 'Competição', 3),
('Final de Futsal', 'Esporte', 4),
('Inteligência Artificial na Saúde', 'Palestra', 5);

-- Atividade_Palestrante
INSERT INTO Atividade_Palestrante (id_atividade, id_palestrante) VALUES
(1, 1),
(1, 2),
(3, 3),
(5, 4),
(5, 5);

-- Inscricao
INSERT INTO Inscricao (id_participante, id_evento, status, data_inscricao) VALUES
(1, 1, 'Confirmado', '2025-07-20'),
(2, 2, 'Confirmado', '2025-07-21'),
(3, 3, 'Pendente', '2025-07-22'),
(4, 4, 'Confirmado', '2025-07-23'),
(5, 5, 'Confirmado', '2025-07-24');

-- Organiza_Evento
INSERT INTO Organiza_Evento (id_organizador, id_evento) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Certificado
INSERT INTO Certificado (data_emissao, carga_horaria, id_participante, id_evento) VALUES
('2025-08-15', 10, 1, 1),
('2025-09-18', 8, 2, 2),
('2025-10-08', 12, 3, 3),
('2025-11-25', 5, 4, 4),
('2025-12-06', 15, 5, 5);

-- Feedback
INSERT INTO Feedback (nota, comentario, id_participante, id_evento) VALUES
(9, 'Excelente evento!', 1, 1),
(8, 'Muito organizado.', 2, 2),
(7, 'Faltou mais tempo.', 3, 3),
(10, 'Evento incrível!', 4, 4),
(9, 'Gostei bastante.', 5, 5);

-- Premiacao
INSERT INTO Premiacao (id_evento, id_participante, colocacao, premio) VALUES
(1, 1, 1, 100.00),
(2, 2, 2, 50.00),
(3, 3, 1, 200.00),
(4, 4, 3, 30.00),
(5, 5, 2, 70.00);
