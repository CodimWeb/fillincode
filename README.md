Run project
docker compose up -d
docker compose exec php composer install
docker compose exec php php artisan migrate
docker compose exec php php artisan db:seed
sudo chmod -R 777 ./storage
docker compose exec php php artisan l5-swagger:generate
