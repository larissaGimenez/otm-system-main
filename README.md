# OTM System

Aplicacao Laravel para gestao de clientes, PDVs, equipamentos e chamados internos. Inclui cadastro de contratos, acompanhamento de mensalidades, ids externos, areas/equipes e um dashboard simples para prioridades do suporte.

## Stack
- PHP 8.2 / Laravel 12 / Livewire 3
- Banco: MySQL 8 (Docker) ou SQLite (desenvolvimento rapido)
- Cache/filas: Redis
- Frontend: Vite, Bootstrap 5, Alpine.js
- Docker: nginx + php-fpm + MySQL + Redis + worker de filas

## Requisitos para ambiente local
- PHP 8.2+, Composer
- Node 18+ e npm
- MySQL ou SQLite (arquivo `database/database.sqlite` basta existir)
- Redis (opcional em desenvolvimento; filas padrao usam database)

## Subindo localmente (sem Docker)
1) Copie o `.env`: `cp .env.example .env` e ajuste `APP_URL`, `DB_*` e `QUEUE_CONNECTION` conforme preferencia (SQLite ja esta pronto para uso local).  
2) Dependencias PHP: `composer install`  
3) Dependencias JS: `npm install`  
4) Gere a chave: `php artisan key:generate`  
5) Migre e popule: `php artisan migrate --seed`  
6) Desenvolvimento: use `composer dev` (serve + queue + logs + vite) ou abra em terminais separados `php artisan serve`, `npm run dev` e `php artisan queue:work`.

Usuario admin padrao (seed):
- Email: `teste@teste.com.br`
- Senha: `12345678`

## Rodando com Docker
1) Certifique-se de ter Docker/Docker Compose e crie a rede externa usada pelo Traefik se ainda nao existir: `docker network create traefik_public`.  
2) Copie `.env` e defina `DB_CONNECTION=mysql`, `DB_HOST=db`, `DB_USERNAME`/`DB_PASSWORD` e demais chaves.  
3) Suba os servicos: `docker compose up -d --build`.  
4) Rode migrations/seed: `docker compose exec app php artisan migrate --seed`.  

O compose foi pensado para ser exposto via Traefik com o host `api.boxfarma.com`. Se preferir acessar direto, adicione mapeamento de portas no servico `nginx` ou configure seu reverse proxy.

## Scripts uteis
- `composer dev` — servidor PHP, filas, logs e Vite em paralelo.
- `npm run dev` / `npm run build` — assets.
- `composer test` — limpa configuracoes e executa `php artisan test`.

## Testes
Execute `composer test`. Para um ciclo mais rapido, pode rodar `php artisan test` diretamente; configure o banco de testes no `.env` ou `.env.testing`.

