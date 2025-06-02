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
 - Then Access: server_domain_or_IP:8800
  
**Without Docker**\
 - `cp .env.example .env`\
 - `composer install`\
 - `./vendor/bin/pint`\
 - `php artisan migrate`\
 - `php artisan db:seed`\
 - `php artisan serve`\
 - `php artisan test`\
 - Then Access: server_domain_or_IP:8800

