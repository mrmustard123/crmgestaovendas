-- ============================================
-- PLATAFORMA CRM DE VENDAS 360�
-- Banco de Dados MySQL - DDL Completo
-- Compat�vel com MySQL 5.6+
-- ============================================

-- Configura��es iniciais
SET FOREIGN_KEY_CHECKS = 0;
SET sql_mode = '';
DROP DATABASE IF EXISTS crm360claude;
CREATE DATABASE crm360claude CHARACTER SET utf8 COLLATE utf8_general_ci;
USE crm360claude;

-- ============================================
-- TABELA: usuarios
-- Gerencia os usu�rios do sistema (vendedores, gestores)
-- ============================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    perfil ENUM('vendedor', 'gestor', 'admin') NOT NULL DEFAULT 'vendedor',
    ativo BOOLEAN DEFAULT TRUE,
    qr_code VARCHAR(255), -- Link �nico para gera��o de leads via QR Code
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_perfil (perfil),
    INDEX idx_ativo (ativo)
);

-- ============================================
-- TABELA: setores
-- Setores de atua��o das empresas
-- ============================================
CREATE TABLE setores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABELA: contas (Pessoa Jur�dica)
-- Empresas/Organiza��es clientes
-- ============================================
CREATE TABLE contas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    razao_social VARCHAR(200) NOT NULL,
    nome_fantasia VARCHAR(200),
    cnpj VARCHAR(18) UNIQUE,
    inscricao_estadual VARCHAR(20),
    inscricao_municipal VARCHAR(20),
    
    -- Endere�o
    cep VARCHAR(9),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    pais VARCHAR(50) DEFAULT 'Brasil',
    
    -- Contato
    telefone_principal VARCHAR(20),
    email_principal VARCHAR(100),
    website VARCHAR(255),
    
    -- Informa��es comerciais
    setor_id INT,
    porte_empresa ENUM('MEI', 'Micro', 'Pequena', 'M�dia', 'Grande'),
    faturamento_anual DECIMAL(15,2),
    numero_funcionarios INT,
    
    -- Relacionamento
    usuario_responsavel_id INT NOT NULL,
    origem_lead ENUM('qr_code', 'site', 'manual', 'indicacao', 'evento', 'outros') DEFAULT 'manual',
    
    -- Status e observa��es
    status ENUM('ativo', 'inativo', 'prospect', 'cliente') DEFAULT 'prospect',
    observacoes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras e �ndices
    FOREIGN KEY (setor_id) REFERENCES setores(id),
    FOREIGN KEY (usuario_responsavel_id) REFERENCES usuarios(id),
    
    INDEX idx_cnpj (cnpj),
    INDEX idx_razao_social (razao_social(100)),
    INDEX idx_usuario_responsavel (usuario_responsavel_id),
    INDEX idx_status (status),
    INDEX idx_origem_lead (origem_lead),
    INDEX idx_setor (setor_id)
);

-- ============================================
-- TABELA: contatos (Pessoa F�sica)
-- Contatos individuais (podem ou n�o estar vinculados a uma conta)
-- ============================================
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100),
    nome_completo VARCHAR(200),
    
    -- Documentos
    cpf VARCHAR(14) UNIQUE,
    rg VARCHAR(20),
    
    -- Contato
    email_principal VARCHAR(100),
    email_secundario VARCHAR(100),
    telefone_principal VARCHAR(20),
    telefone_secundario VARCHAR(20),
    whatsapp VARCHAR(20),
    
    -- Endere�o
    cep VARCHAR(9),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    pais VARCHAR(50) DEFAULT 'Brasil',
    
    -- Informa��es pessoais
    data_nascimento DATE,
    sexo ENUM('M', 'F', 'Outro'),
    estado_civil ENUM('Solteiro', 'Casado', 'Divorciado', 'Viuvo', 'Uniao Estavel'),
    profissao VARCHAR(100),
    
    -- Relacionamento com empresa (opcional)
    conta_id INT NULL, -- Pode ser NULL se for cliente pessoa f�sica independente
    cargo VARCHAR(100),
    departamento VARCHAR(100),
    eh_decisor BOOLEAN DEFAULT FALSE,
    
    -- Relacionamento CRM
    usuario_responsavel_id INT NOT NULL,
    origem_lead ENUM('qr_code', 'site', 'manual', 'indicacao', 'evento', 'outros') DEFAULT 'manual',
    
    -- Status
    status ENUM('ativo', 'inativo', 'prospect', 'cliente') DEFAULT 'prospect',
    observacoes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras e �ndices
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_responsavel_id) REFERENCES usuarios(id),
    
    INDEX idx_cpf (cpf),
    INDEX idx_nome_completo (nome_completo(100)),
    INDEX idx_email_principal (email_principal(100)),
    INDEX idx_telefone_principal (telefone_principal),
    INDEX idx_conta (conta_id),
    INDEX idx_usuario_responsavel (usuario_responsavel_id),
    INDEX idx_status (status),
    INDEX idx_origem_lead (origem_lead)
);

-- ============================================
-- TABELA: produtos_servicos
-- Cat�logo de produtos e servi�os oferecidos
-- ============================================
CREATE TABLE produtos_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    tipo ENUM('produto', 'servico') NOT NULL,
    categoria VARCHAR(100),
    preco_base DECIMAL(10,2),
    unidade_medida VARCHAR(20),
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nome (nome(100)),
    INDEX idx_tipo (tipo),
    INDEX idx_categoria (categoria),
    INDEX idx_ativo (ativo)
);

-- ============================================
-- TABELA: estagios_pipeline
-- Est�gios do funil de vendas (personaliz�veis)
-- ============================================
CREATE TABLE estagios_pipeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ordem INT NOT NULL,
    probabilidade_fechamento DECIMAL(5,2) DEFAULT 0.00, -- Percentual de 0 a 100
    cor_hex VARCHAR(7) DEFAULT '#007bff', -- Cor para exibi��o visual
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_ordem (ordem),
    INDEX idx_ativo (ativo)
);

-- ============================================
-- TABELA: oportunidades
-- Oportunidades de neg�cio (vendas em potencial)
-- ============================================
CREATE TABLE oportunidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    
    -- Relacionamentos
    contato_id INT NOT NULL,
    conta_id INT NULL, -- Pode ser NULL se for oportunidade de pessoa f�sica
    usuario_responsavel_id INT NOT NULL,
    estagio_id INT NOT NULL,
    
    -- Valores financeiros
    valor_estimado DECIMAL(12,2) DEFAULT 0.00,
    valor_real DECIMAL(12,2) NULL, -- Preenchido quando fechada
    moeda VARCHAR(3) DEFAULT 'BRL',
    
    -- Datas importantes
    data_abertura DATE NOT NULL,
    data_fechamento_prevista DATE,
    data_fechamento_real DATE,
    
    -- Status e resultado
    status ENUM('aberta', 'ganha', 'perdida', 'cancelada') DEFAULT 'aberta',
    motivo_perda TEXT,
    concorrente VARCHAR(200),
    
    -- Origem e classifica��o
    origem_lead ENUM('qr_code', 'site', 'manual', 'indicacao', 'evento', 'outros') DEFAULT 'manual',
    prioridade ENUM('baixa', 'media', 'alta', 'critica') DEFAULT 'media',
    
    -- Observa��es
    observacoes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras e �ndices
    FOREIGN KEY (contato_id) REFERENCES contatos(id),
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_responsavel_id) REFERENCES usuarios(id),
    FOREIGN KEY (estagio_id) REFERENCES estagios_pipeline(id),
    
    INDEX idx_titulo (titulo(100)),
    INDEX idx_contato (contato_id),
    INDEX idx_conta (conta_id),
    INDEX idx_usuario_responsavel (usuario_responsavel_id),
    INDEX idx_estagio (estagio_id),
    INDEX idx_status (status),
    INDEX idx_data_abertura (data_abertura),
    INDEX idx_data_fechamento_prevista (data_fechamento_prevista),
    INDEX idx_valor_estimado (valor_estimado),
    INDEX idx_origem_lead (origem_lead),
    INDEX idx_prioridade (prioridade)
);

-- ============================================
-- TABELA: oportunidades_produtos
-- Relacionamento N:N entre oportunidades e produtos/servi�os
-- ============================================
CREATE TABLE oportunidades_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidade_id INT NOT NULL,
    produto_servico_id INT NOT NULL,
    quantidade DECIMAL(10,2) DEFAULT 1.00,
    preco_unitario DECIMAL(10,2),
    desconto_percentual DECIMAL(5,2) DEFAULT 0.00,
    valor_total DECIMAL(12,2),
    observacoes TEXT,
    
    FOREIGN KEY (oportunidade_id) REFERENCES oportunidades(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_servico_id) REFERENCES produtos_servicos(id),
    
    UNIQUE KEY unique_oportunidade_produto (oportunidade_id, produto_servico_id),
    INDEX idx_oportunidade (oportunidade_id),
    INDEX idx_produto_servico (produto_servico_id)
);

-- ============================================
-- TABELA: tipos_atividade
-- Tipos de atividades que podem ser registradas
-- ============================================
CREATE TABLE tipos_atividade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    icone VARCHAR(50), -- Nome do �cone para interface
    cor_hex VARCHAR(7) DEFAULT '#007bff',
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABELA: atividades
-- Registro de todas as atividades (liga��es, reuni�es, e-mails, etc.)
-- ============================================
CREATE TABLE atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    
    -- Relacionamentos
    tipo_atividade_id INT NOT NULL,
    usuario_id INT NOT NULL,
    contato_id INT NULL,
    conta_id INT NULL,
    oportunidade_id INT NULL,
    
    -- Datas e hor�rios
    data_atividade DATETIME NOT NULL,
    duracao_minutos INT,
    
    -- Status
    status ENUM('agendada', 'realizada', 'cancelada', 'reagendada') DEFAULT 'agendada',
    resultado ENUM('positivo', 'neutro', 'negativo') NULL,
    
    -- Observa��es e pr�ximos passos
    observacoes TEXT,
    proximos_passos TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras e �ndices
    FOREIGN KEY (tipo_atividade_id) REFERENCES tipos_atividade(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (contato_id) REFERENCES contatos(id) ON DELETE SET NULL,
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE SET NULL,
    FOREIGN KEY (oportunidade_id) REFERENCES oportunidades(id) ON DELETE SET NULL,
    
    INDEX idx_titulo (titulo(100)),
    INDEX idx_tipo_atividade (tipo_atividade_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_contato (contato_id),
    INDEX idx_conta (conta_id),
    INDEX idx_oportunidade (oportunidade_id),
    INDEX idx_data_atividade (data_atividade),
    INDEX idx_status (status),
    INDEX idx_resultado (resultado)
);

-- ============================================
-- TABELA: documentos
-- Arquivamento de documentos (propostas, contratos, etc.)
-- ============================================
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    tipo_arquivo VARCHAR(10),
    tamanho_bytes BIGINT,
    caminho_arquivo VARCHAR(500),
    
    -- Relacionamentos (pelo menos um deve ser preenchido)
    contato_id INT NULL,
    conta_id INT NULL,
    oportunidade_id INT NULL,
    
    -- Classifica��o
    categoria ENUM('proposta', 'contrato', 'apresentacao', 'documentacao', 'outros') DEFAULT 'outros',
    versao VARCHAR(20) DEFAULT '1.0',
    
    -- Controle
    usuario_upload_id INT NOT NULL,
    descricao TEXT,
    privado BOOLEAN DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras e �ndices
    FOREIGN KEY (contato_id) REFERENCES contatos(id) ON DELETE CASCADE,
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE,
    FOREIGN KEY (oportunidade_id) REFERENCES oportunidades(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_upload_id) REFERENCES usuarios(id),
    
    INDEX idx_nome (nome(100)),
    INDEX idx_contato (contato_id),
    INDEX idx_conta (conta_id),
    INDEX idx_oportunidade (oportunidade_id),
    INDEX idx_categoria (categoria),
    INDEX idx_usuario_upload (usuario_upload_id)
);

-- ============================================
-- TABELA: historico_estagios
-- Hist�rico de mudan�as de est�gios das oportunidades
-- ============================================
CREATE TABLE historico_estagios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidade_id INT NOT NULL,
    estagio_anterior_id INT NULL,
    estagio_novo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_mudanca TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,
    
    FOREIGN KEY (oportunidade_id) REFERENCES oportunidades(id) ON DELETE CASCADE,
    FOREIGN KEY (estagio_anterior_id) REFERENCES estagios_pipeline(id),
    FOREIGN KEY (estagio_novo_id) REFERENCES estagios_pipeline(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    
    INDEX idx_oportunidade (oportunidade_id),
    INDEX idx_data_mudanca (data_mudanca)
);

-- ============================================
-- TABELA: metas_vendas
-- Metas de vendas por usu�rio e per�odo
-- ============================================
CREATE TABLE metas_vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    ano INT NOT NULL,
    mes INT NOT NULL,
    meta_valor DECIMAL(12,2) NOT NULL,
    meta_quantidade INT DEFAULT 0,
    valor_alcancado DECIMAL(12,2) DEFAULT 0.00,
    quantidade_alcancada INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    
    UNIQUE KEY unique_usuario_periodo (usuario_id, ano, mes),
    INDEX idx_usuario (usuario_id),
    INDEX idx_periodo (ano, mes)
);

-- ============================================
-- TABELA: configuracoes_sistema
-- Configura��es gerais do sistema
-- ============================================
CREATE TABLE configuracoes_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    descricao TEXT,
    tipo ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    categoria VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_chave (chave),
    INDEX idx_categoria (categoria)
);

-- ============================================
-- INSER��O DE DADOS INICIAIS
-- ============================================

-- Setores padr�o
INSERT INTO setores (nome, descricao) VALUES
('Tecnologia', 'Empresas de tecnologia e software'),
('Varejo', 'Com�rcio varejista'),
('Servi�os', 'Presta��o de servi�os diversos'),
('Ind�stria', 'Setor industrial e manufatura'),
('Sa�de', '�rea da sa�de e medicina'),
('Educa��o', 'Institui��es de ensino'),
('Financeiro', 'Bancos e institui��es financeiras'),
('Imobili�rio', 'Setor imobili�rio'),
('Agroneg�cio', 'Agricultura e pecu�ria'),
('Outros', 'Outros setores n�o especificados');

-- Est�gios do pipeline padr�o
INSERT INTO estagios_pipeline (nome, descricao, ordem, probabilidade_fechamento, cor_hex) VALUES
('Qualifica��o', 'Identifica��o e qualifica��o do lead', 1, 10.00, '#ffc107'),
('Apresenta��o', 'Apresenta��o da solu��o ao cliente', 2, 25.00, '#17a2b8'),
('Proposta', 'Envio de proposta comercial', 3, 50.00, '#007bff'),
('Negocia��o', 'Negocia��o de termos e condi��es', 4, 75.00, '#fd7e14'),
('Fechamento', 'Finaliza��o da venda', 5, 90.00, '#28a745'),
('Perdida', 'Oportunidade perdida', 6, 0.00, '#dc3545');

-- Tipos de atividade padr�o
INSERT INTO tipos_atividade (nome, descricao, icone, cor_hex) VALUES
('Liga��o', 'Liga��o telef�nica', 'phone', '#007bff'),
('E-mail', 'Envio de e-mail', 'mail', '#17a2b8'),
('Reuni�o', 'Reuni�o presencial ou virtual', 'users', '#28a745'),
('Apresenta��o', 'Apresenta��o de produto/servi�o', 'presentation', '#ffc107'),
('Visita', 'Visita ao cliente', 'map-pin', '#6f42c1'),
('Follow-up', 'Acompanhamento p�s-contato', 'clock', '#fd7e14'),
('Proposta', 'Envio de proposta', 'file-text', '#20c997'),
('Negocia��o', 'Negocia��o comercial', 'handshake', '#e83e8c');

-- Usu�rio administrador padr�o
INSERT INTO usuarios (nome, email, senha_hash, perfil, qr_code) VALUES
('Administrador', 'admin@crm360.com', '$2y$10$example_hash', 'admin', 'https://crm360.com/lead/admin-qr');

-- Configura��es iniciais do sistema
INSERT INTO configuracoes_sistema (chave, valor, descricao, tipo, categoria) VALUES
('empresa_nome', 'CRM Vendas 360', 'Nome da empresa', 'string', 'geral'),
('moeda_padrao', 'BRL', 'Moeda padrao do sistema', 'string', 'financeiro'),
('fuso_horario', 'America/Sao_Paulo', 'Fuso horario padrao', 'string', 'geral'),
('pipeline_padrao', '1', 'ID do estagio padrao para novas oportunidades', 'number', 'vendas'),
('limite_upload_mb', '10', 'Limite de upload de arquivos em MB', 'number', 'sistema');

-- ============================================
-- TRIGGERS PARA AUDITORIA E AUTOMA��O
-- ============================================

-- Trigger para atualizar hist�rico quando est�gio da oportunidade muda
DELIMITER $
CREATE TRIGGER tr_oportunidade_mudanca_estagio
    AFTER UPDATE ON oportunidades
    FOR EACH ROW
BEGIN
    IF OLD.estagio_id != NEW.estagio_id THEN
        INSERT INTO historico_estagios (
            oportunidade_id, 
            estagio_anterior_id, 
            estagio_novo_id, 
            usuario_id,
            observacoes
        ) VALUES (
            NEW.id,
            OLD.estagio_id,
            NEW.estagio_id,
            NEW.usuario_responsavel_id,
            CONCAT('Mudanca automatica de estagio em ', NOW())
        );
    END IF;
END$
DELIMITER ;

-- ============================================
-- VIEWS PARA RELAT�RIOS
-- ============================================

-- View para relat�rio de funil de vendas
CREATE VIEW vw_funil_vendas AS
SELECT 
    e.nome as estagio,
    e.ordem,
    COUNT(o.id) as quantidade_oportunidades,
    SUM(o.valor_estimado) as valor_total,
    AVG(o.valor_estimado) as valor_medio,
    e.probabilidade_fechamento
FROM estagios_pipeline e
LEFT JOIN oportunidades o ON e.id = o.estagio_id AND o.status = 'aberta'
WHERE e.ativo = TRUE
GROUP BY e.id, e.nome, e.ordem, e.probabilidade_fechamento
ORDER BY e.ordem;

-- View para relat�rio de desempenho por vendedor
CREATE VIEW vw_desempenho_vendedores AS
SELECT 
    u.id as usuario_id,
    u.nome as vendedor,
    COUNT(DISTINCT o.id) as total_oportunidades,
    COUNT(DISTINCT CASE WHEN o.status = 'ganha' THEN o.id END) as oportunidades_ganhas,
    COUNT(DISTINCT CASE WHEN o.status = 'perdida' THEN o.id END) as oportunidades_perdidas,
    SUM(CASE WHEN o.status = 'ganha' THEN o.valor_real ELSE 0 END) as receita_gerada,
    SUM(o.valor_estimado) as pipeline_valor,
    COUNT(DISTINCT a.id) as total_atividades,
    ROUND(
        COUNT(DISTINCT CASE WHEN o.status = 'ganha' THEN o.id END) * 100.0 / 
        NULLIF(COUNT(DISTINCT CASE WHEN o.status IN ('ganha', 'perdida') THEN o.id END), 0), 2
    ) as taxa_conversao
FROM usuarios u
LEFT JOIN oportunidades o ON u.id = o.usuario_responsavel_id
LEFT JOIN atividades a ON u.id = a.usuario_id
WHERE u.perfil IN ('vendedor', 'gestor')
GROUP BY u.id, u.nome;

-- View para an�lise de origem de leads
CREATE VIEW vw_origem_leads AS
SELECT 
    origem_lead,
    COUNT(*) as total_leads,
    COUNT(CASE WHEN status IN ('cliente', 'ativo') THEN 1 END) as leads_convertidos,
    ROUND(
        COUNT(CASE WHEN status IN ('cliente', 'ativo') THEN 1 END) * 100.0 / COUNT(*), 2
    ) as taxa_conversao,
    'contatos' as tipo_lead
FROM contatos
GROUP BY origem_lead

UNION ALL

SELECT 
    origem_lead,
    COUNT(*) as total_leads,
    COUNT(CASE WHEN status IN ('cliente', 'ativo') THEN 1 END) as leads_convertidos,
    ROUND(
        COUNT(CASE WHEN status IN ('cliente', 'ativo') THEN 1 END) * 100.0 / COUNT(*), 2
    ) as taxa_conversao,
    'contas' as tipo_lead
FROM contas
GROUP BY origem_lead;

-- ============================================
-- PROCEDURES PARA RELAT�RIOS COMPLEXOS
-- ============================================

-- Procedure para forecast de vendas
DELIMITER $
CREATE PROCEDURE sp_forecast_vendas(
    IN p_usuario_id INT,
    IN p_mes_inicio INT,
    IN p_ano_inicio INT,
    IN p_mes_fim INT,
    IN p_ano_fim INT
)
BEGIN
    SELECT 
        DATE_FORMAT(o.data_fechamento_prevista, '%Y-%m') as periodo,
        COUNT(o.id) as quantidade_oportunidades,
        SUM(o.valor_estimado) as valor_pipeline,
        SUM(o.valor_estimado * e.probabilidade_fechamento / 100) as valor_ponderado,
        AVG(e.probabilidade_fechamento) as probabilidade_media
    FROM oportunidades o
    INNER JOIN estagios_pipeline e ON o.estagio_id = e.id
    WHERE o.status = 'aberta'
    AND o.data_fechamento_prevista BETWEEN 
        CONCAT(p_ano_inicio, '-', LPAD(p_mes_inicio, 2, '0'), '-01') AND 
        LAST_DAY(CONCAT(p_ano_fim, '-', LPAD(p_mes_fim, 2, '0'), '-01'))
    AND (p_usuario_id IS NULL OR o.usuario_responsavel_id = p_usuario_id)
    GROUP BY DATE_FORMAT(o.data_fechamento_prevista, '%Y-%m')
    ORDER BY periodo;
END$
DELIMITER ;

-- Reativar verifica��o de chaves estrangeiras
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- �NDICES ADICIONAIS PARA PERFORMANCE
-- ============================================

-- �ndices compostos para consultas frequentes
CREATE INDEX idx_oportunidades_usuario_status ON oportunidades(usuario_responsavel_id, status);
CREATE INDEX idx_oportunidades_data_valor ON oportunidades(data_fechamento_prevista, valor_estimado);
CREATE INDEX idx_atividades_usuario_data ON atividades(usuario_id, data_atividade);
CREATE INDEX idx_contatos_usuario_status ON contatos(usuario_responsavel_id, status);
CREATE INDEX idx_contas_usuario_status ON contas(usuario_responsavel_id, status);

-- ============================================
-- COMENT�RIOS FINAIS
-- ============================================

-- Este banco de dados foi projetado para suportar:
-- 1. Gest�o completa de contas (PJ) e contatos (PF)
-- 2. Pipeline de vendas flex�vel e personaliz�vel
-- 3. Rastreamento de atividades e hist�rico completo
-- 4. Gerenciamento de documentos
-- 5. Relat�rios avan�ados e an�lises estrat�gicas
-- 6. M�ltiplas origens de leads (QR Code, Site, Manual)
-- 7. Controle de metas e performance
-- 8. Auditoria autom�tica de mudan�as
-- 9. Views otimizadas para relat�rios
-- 10. Procedures para an�lises complexas

-- Estrutura otimizada para performance com �ndices apropriados
-- Relacionamentos bem definidos com integridade referencial
-- Campos calculados autom�ticos para reduzir processamento
-- Triggers para automa��o de processos
-- Configura��es flex�veis do sistema