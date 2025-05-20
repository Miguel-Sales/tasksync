# TaskSync

**TaskSync** é um sistema de gerenciamento de tarefas que permite a usuários cadastrados criarem, visualizarem e organizarem suas atividades de forma eficiente com base em prioridades, setores e status.

## 🚀 Funcionalidades

- Cadastro e login de usuários
- Criação e listagem de tarefas
- Atribuição de prioridade, setor e status às tarefas
- Filtro por status ou prioridade
- Registro de data e hora de criação

## 🗂️ Estrutura do Banco de Dados

### Tabela: `usuario`
- `id_usuario`: `int(11)` – Chave primária
- `nome_usuario`: `varchar(100)`
- `email_usuario`: `varchar(100)`
- `senha_usuario`: `varchar(100)`

### Tabela: `tarefas`
- `id`: `int(11)` – Chave primária
- `usuario_id`: `int(11)` – Chave estrangeira (`usuario.id_usuario`)
- `descricao`: `text`
- `setor`: `varchar(100)`
- `prioridade`: `enum('Baixa','Média','Alta')`
- `data_cadastro`: `datetime`
- `status`: `enum('A Fazer','Fazendo','Concluído')`

## 🛠️ Tecnologias Utilizadas

- PHP
- MySQL
- HTML/CSS
- JavaScript
- Bootstrap (se aplicável)

## 📦 Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/Miguel-Sales/tasksync.git
