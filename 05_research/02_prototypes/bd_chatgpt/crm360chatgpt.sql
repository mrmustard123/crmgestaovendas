-- =========================
-- TABELAS BASE
-- =========================

CREATE TABLE contas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    razao_social VARCHAR(255) NOT NULL,
    cnpj VARCHAR(20) NOT NULL UNIQUE,
    setor VARCHAR(100),
    endereco TEXT,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(20) UNIQUE,
    email VARCHAR(255),
    telefone VARCHAR(20),
    id_conta INT DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conta) REFERENCES contas(id) ON DELETE SET NULL
);

-- =========================
-- OPORTUNIDADES E PIPELINE
-- =========================

CREATE TABLE oportunidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    id_conta INT DEFAULT NULL,
    id_contato INT DEFAULT NULL,
    valor_estimado DECIMAL(12,2),
    data_prevista DATE,
    status VARCHAR(50) DEFAULT 'Qualificação',
    produtos_servicos TEXT,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conta) REFERENCES contas(id) ON DELETE SET NULL,
    FOREIGN KEY (id_contato) REFERENCES contatos(id) ON DELETE SET NULL
);

-- =========================
-- PIPELINE (estágios do Kanban)
-- =========================

CREATE TABLE estagios_pipeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ordem INT NOT NULL
);

CREATE TABLE pipeline_oportunidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_oportunidade INT NOT NULL,
    id_estagio INT NOT NULL,
    data_movimentacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_oportunidade) REFERENCES oportunidades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_estagio) REFERENCES estagios_pipeline(id)
);

-- =========================
-- LEADS & CANAIS
-- =========================

CREATE TABLE canais_lead (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origem_id INT NOT NULL,
    id_contato INT,
    id_conta INT,
    criado_por_qrcode BOOLEAN DEFAULT FALSE,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (origem_id) REFERENCES canais_lead(id),
    FOREIGN KEY (id_contato) REFERENCES contatos(id),
    FOREIGN KEY (id_conta) REFERENCES contas(id)
);

-- =========================
-- ATIVIDADES
-- =========================

CREATE TABLE atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('ligacao', 'email', 'reuniao', 'outro') NOT NULL,
    descricao TEXT,
    data DATETIME NOT NULL,
    id_contato INT DEFAULT NULL,
    id_oportunidade INT DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_contato) REFERENCES contatos(id) ON DELETE SET NULL,
    FOREIGN KEY (id_oportunidade) REFERENCES oportunidades(id) ON DELETE SET NULL
);

-- =========================
-- DOCUMENTOS
-- =========================

CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_arquivo VARCHAR(255) NOT NULL,
    caminho_arquivo VARCHAR(255) NOT NULL,
    tipo ENUM('proposta', 'contrato', 'outro') NOT NULL,
    id_oportunidade INT DEFAULT NULL,
    id_contato INT DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_oportunidade) REFERENCES oportunidades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_contato) REFERENCES contatos(id) ON DELETE SET NULL
);

-- =========================
-- VENDEDORES E RELATÓRIOS
-- =========================

CREATE TABLE vendedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE oportunidades ADD COLUMN id_vendedor INT DEFAULT NULL;
ALTER TABLE oportunidades ADD FOREIGN KEY (id_vendedor) REFERENCES vendedores(id);

-- Este campo pode ser usado para relatórios de desempenho por vendedor
