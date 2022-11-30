# Symfony - Private Messages 
App with Symfony and Docker. Made to learn.

<br>

 <img src="https://user-images.githubusercontent.com/102531037/204605571-bebf1060-4c44-4fa3-b615-f18f5d16d5bc.gif" width="600"/>



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
<img width="500" alt="image" src="https://user-images.githubusercontent.com/102531037/204749936-5fac3903-1736-48f6-b927-d2250bfdbc64.png">


### Author

[@francoiscoche](https://github.com/francoiscoche)
