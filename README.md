# Symfony - Private Messages 
App with Symfony and Docker. Made to learn.

<br>

*Developpement in progress ...*



<br>

### Requirements

- Docker
- Docker-compose

<br>

### Run the project locally with docker
<br>

Clone the project
```bash
git clone git@github.com:francoiscoche/private-messages.git
```

Run docker-compose
```bash
docker-compose build
docker-compose up -d
```

Copy the vendor folder to the container (did for performance purpose)
```bash
cd .\project\
composer install
docker cp vendor php8-private-messages:/var/www/app
docker cp var php8-private-messages:/var/www/app
```

Log into the PHP container
```bash
docker exec -it php8-private-messages bash
```

Run the migrations (need to configure database into .env.local)
```bash
php bin/console doctrine:migrations:migrate
```

Run the fixtures 
```bash
php bin/console doctrine:fixtures:load
```


*The application is available at http://127.0.0.1:8000*

<br><br>





### Author

[@francoiscoche](https://github.com/francoiscoche)
