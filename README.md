# 🚀 MySpace

Descrição breve do projeto.
MySpace é uma aplicação inspirada em redes sociais, onde os usuários podem criar e compartilhar postagens contendo texto e imagens. Todas as publicações ficam visíveis para todos os usuários, promovendo interação e compartilhamento de conteúdo. Cada usuário tem controle total apenas sobre suas próprias postagens, podendo editá-las ou excluí-las a qualquer momento.

## 📦 Requisitos

Instale antes de iniciar:
composer install

⚙️ Configurar Ambiente

Criar o arquivo .env.local

cp .env .env.local
DATABASE_URL="mysql://usuario:senha@127.0.0.1:3306/nome_do_banco"

Ou PostgreSQL:

DATABASE_URL="postgresql://usuario:senha@127.0.0.1:5432/nome_do_banco?serverVersion=15&charset=utf8"

🗄️ Criar Banco e Migrations
Criar banco:

php bin/console doctrine:database:create

Aplicar migrations:

php bin/console doctrine:migrations:migrate

Carregar fixtures (se existir):

🔧 Limpar Cache

php bin/console cache:clear

▶️ Rodar a Aplicação

symfony server:start

Acesse no navegador:
http://127.0.0.1:8000
