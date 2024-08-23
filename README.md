<h2>Run project</h2>
<p>docker compose up -d</p> 
<p>docker compose exec php composer install</p>
<p>docker compose exec php php artisan migrate</p>
<p>docker compose exec php php artisan db:seed</p>
<p>sudo chmod -R 777 ./storage</p>
<p>docker compose exec php php artisan l5-swagger:generate</p>
