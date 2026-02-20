# API Campeonato de Futebol

API REST desenvolvida para gerenciamento e simulação de campeonatos no formato mata-mata.

O sistema permite:

- Cadastro de campeonatos
- Cadastro de times
- Sorteio automático de confrontos
- Registro de resultados
- Cálculo automático de vencedores
- Visualização dos resultados por fase

---

# Tecnologias Utilizadas

- PHP 8.5.3
- Laravel Framework 12.51.0
- Composer 2.9.5
- PostgreSQL
- AWS RDS (Banco de Dados)

---

# Pré-requisitos

Antes de executar o projeto, é necessário ter instalado:

- PHP 8.5+
- Composer
- PostgreSQL
- Git

---

# Instalação do Projeto

1. Clone o repositório
git clone https://github.com/lucasmp72/api-campeonato-futebol.git

2. Entre na pasta
cd campeonato-api

3. Instale as dependências
composer install

4. Copie o arquivo .env
cp .env.example .env

5. Configure o arquivo .env

Exemplo de configuração:

APP_NAME=CampeonatoAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=seu-host-aqui
DB_PORT=5432
DB_DATABASE=seu-banco
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha

6. Gere a chave da aplicação

php artisan key:generate

7. Execute o projeto

php artisan serve

---

# Banco de dados

O projeto foi desenvolvido utilizando abordagem DB First.

O banco de dados está hospedado na AWS RDS (PostgreSQL).

Estrutura Principal:

### Tabela: campeonatos

* id (PK)

* nome (varchar)

* data_criacao (timestamp)

* ativo (boolean)

### Tabela: times

* id (PK)

* nome (varchar)

* data_criacao (timestamp)

* ativo (boolean)

### Tabela: campeonatos_times

* id (PK)

* campeonato_id (FK)

* time_id (FK)

* data_criacao (timestamp)

### Tabela: partidas

* id (PK)

* campeonato_id (FK)

* time_casa_id (FK)

* time_visitante_id (FK)

* gols_casa (integer)

* gols_visitante (integer)

* penaltis_casa (integer)

* penaltis_visitante (integer)

* fase_id (integer)

* data_criacao (timestamp)

* ativo (boolean)

### Tabela: fases

* id (PK)

* nome (varchar)

* data_criacao (timestamp)

* ativo (boolean)

---

# Regras de Negócio

* O campeonato funciona no formato mata-mata.

* Cada fase elimina metade dos times.

* Não é permitido iniciar campeonato com menos de 8 times.

* Confrontos são sorteados aleatoriamente.

* Empates são decididos nos pênaltis.

---

# Endpoints da API

A collection para testes da API está disponível na pasta:

/docs/api-campeonato-futebol.postman_collection.json

Basta importar no Postman para testar todos os endpoints.