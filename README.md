# ✈️ Sistema de Gerenciamento de Pedidos de Viagem

Este projeto é uma aplicação **Full Stack** desenvolvida com **Laravel 12** no back-end e **Vue.js 3** no front-end. Ele permite que usuários realizem solicitações de viagem e que administradores façam o gerenciamento completo dos pedidos.

---

## 🚀 Funcionalidades

- Login e registro de usuários
- Criação e listagem de pedidos de viagem
- Filtros por destino, data e status
- Atualização de status (admin)
- Cancelamento de pedidos pendentes (usuário)
- Interface moderna e intuitiva
- Testes automatizados

---

## 🧱 Tecnologias Utilizadas

### Back-end
- Laravel 12
- Laravel Sanctum
- MySQL 8 (via Docker)
- PHPUnit

### Front-end
- Vue.js 3 + Vite
- Vue Router
- Axios
- Tailwind CSS (opcional)

### Infraestrutura
- Docker + Docker Compose

---

## 📦 Requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## 🔧 Instalação e Execução com Docker

### 1. Clone o repositório

git clone https://github.com/seu-usuario/travel.git
cd seu-projeto

### 2. Crie os arquivos de ambiente

- cp backend/.env.example backend/.env

- Edite as variáveis de conexão com o banco no .env (deixe igual às do docker-compose.yml):

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=travel
DB_USERNAME=root
DB_PASSWORD=secret

### 3. Suba os containers

- docker compose up -d --build

### 4. Migrations & Seeders

- docker-compose exec app php artisan migrate --seed

🌐 Acesso

- API (Laravel): http://localhost:8000
- Front-end (Vue): http://localhost:5173

### Configuração do .env.testing

APP_ENV=testing
APP_KEY=base64:testkey
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

- docker-compose exec app php artisan test