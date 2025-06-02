## How To Install

**Using Docker**\
 - `cp .env.example .env`\
 - `docker-compose build app`\
 - `docker-compose up -d`\
 - `docker-compose exec app composer install`\
 - `docker-compose exec app ./vendor/bin/pint`\
 - `docker-compose exec app php artisan migrate`\
 - `docker-compose exec app php artisan db:seed`\
 - `docker-compose exec app php artisan test`\
 - `docker-compose exec app php artisan l5-swagger:generate`\
  
**Without Docker**\
 - `cp .env.example .env`\
 - `composer install`\
 - `./vendor/bin/pint`\
 - `php artisan migrate`\
 - `php artisan db:seed`\
 - `php artisan serve`\
 - `php artisan test`\
 - `php artisan l5-swagger:generate`\

✅ Acesso:\
Rodar a API:\
➡️ http://server_domain_or_IP:8800\

Visualizar a documentação Swagger:\
➡️ http://server_domain_or_IP:8800/api/documentation\

