# SIMPLE VACANCY APPLICATION


## Technical Specifications

This application has the following specifications: 

| Tool | Version |
| --- | --- |
| Docker | 24.0.7, |
| Docker Compose | 1.29.2 |
| Nginx | 1.19.10 |
| PHP | 8.3.9 |
| Postgre | 16.3 |
| Sqlite (Unit Tests) | 3.16.2 |
| Laravel Framework | 11.14.* |
| Swagger (OpenAPI) | 3.1.* |


The application is separated into the following containers

| Service | Image | Motivation
| --- | --- | --- |
| postgres | postgres:latest | Main database |
| php | php-vacancy | Main Application (Web) |
| web (nginx) | nginx:alpine | Web Server |

## Requirements
    - Docker
    - Docker Daemon (Service)
    - Docker Compose

## Installation procedures
    Procedures for installing the application for local use

1- Download repository 
    - git clone https://github.com/brunocaramelo/vacancy_plan.git
       
    we must copy .env.docker-compose to .env with the command below:

        - cp docker/docker-compose-env/application.env.example docker/docker-compose-env/application.env

        - cp docker/docker-compose-env/database.env.example docker/docker-compose-env/database.env

2 - Check that the ports:

    - 443 (nginx) 
    
    - 9000(php-fpm)

    - 5432(postgres) 

     are busy.


3 - Enter the application's home directory and run the following commands (IMPORTANT):
    
    1 - docker-compose up -d;

    2 - docker exec -t php-vacancy php /app/artisan migrate;

    3 - docker exec -t php-vacancy php /app/artisan db:seed;

    4 - docker exec -t php-vacancy ./vendor/bin/phpunit;

     
### Resolution of possible problems:

#### Problems with dependencies/autoload (Step 1)
    Due to the delay in composer bringing up the dependencies, there are 3 alternatives,
        
            1 - RUN sudo docker-compose up; without being a daemon the first time, so that you can check the progress of the installation of dependencies.
            
            2 - Wait 20 minutes or so for the command to run, in order to avoid autoload errors, for example.
            
            3 - If you have any problems with dependencies, run the command below to secure them.
                sudo docker exec -t php-sample composer install;

#### Problems with Webserver permission to the exposed volume (Step 6)
    - You may also have problems with Webserver permission to the /app volume (or subdirectories)
      Even though it's not indicated, but because it's a local environment, you can force the execution of permissions with:
       - sudo docker-compose exec web chmod 777 -R /app    

## Post Installation

After installation, the access address is (IMPORTANT):

    1 - access https://localhost/api/documentation

    2 - Go to login >> /api/login , execute POST and retrieve value from: access_token

    3 - At the top of the Swagger UI press the Authorize button and paste the value in the field: bearerAuth (http, Bearer) and press Authorize.

    4 - With this you can consult the endepoints of the tags: holidays and report

## Details

    - Laravel 11

    - SOLID

    - Unit Tests

    - Docker and docker-compose

    - Swagger (OpenAPI)

## Observation:

Laravel logs are being directed to the container's stderr, this can be seen by analyzing the container logs or running docker-compose without parameter -d
