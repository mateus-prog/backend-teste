## How To Install

**Using Docker**\
 - `cp .env.example .env`\
 - `docker-compose build app`\
 - `docker-compose up -d`\
 - `docker-compose exec app composer install`\
 - `docker-compose exec app php artisan migrate`\
 - `docker-compose exec app php artisan db:seed`\
  
**Without Docker**\
 - `cp .env.example .env`\
 - `composer install`\
 - `php artisan migrate`\
 - `php artisan db:seed`\
 - `php artisan serve`\

✅ Aplicando boas práticas, documentação e testes:\
Rodar script para verificar erros de sintaxe e padrões de estilo (identação, espaçamento):\
➡️ composer see_linter_errors

Rodar script para aplicar padrões de estilo (identação, espaçamento):\
➡️ composer apply_linter
➡️ vendor/bin/pint

Rodar script para gerar documentação:\
➡️ php artisan l5-swagger:generate

Rodar os testes:\
➡️ php artisan test

✅ Acesso:\
Rodar a API:\
➡️ http://server_domain_or_IP:8800\

Visualizar a documentação Swagger:\
➡️ http://server_domain_or_IP:8800/api/documentation\

