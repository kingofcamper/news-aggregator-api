# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker). 

Clone the repository

    git clone https://github.com/kingofcamper/news-aggregator-api.git

Switch to the repo folder

    cd news-aggregator-api

Install all the dependencies using composer

    composer install

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone https://github.com/kingofcamper/news-aggregator-api.git
    cd news-aggregator-api
    composer install    
    php artisan key:generate    
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

**Generate Articles** (By default the third parties APIs keys are included in **.env**)

    php artisan newsapi:daily-articles <category>
    php artisan newyorktimes:daily-articles <category>
    php artisan guardian:daily-articles <category>

## Docker

To install with [Docker](https://www.docker.com), run following commands:

```
git clone git@github.com:kingofcamper/news-aggregator-api.git
cd news-aggregator-api
docker-compose exec app rm -rf vendor composer.lock
docker run -v $(pwd):/app composer install
cd ./docker
docker-compose up -d
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan serve --host=0.0.0.0
```

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## API Specification

This application adheres to the api specifications set by the [Thinkster](https://github.com/gothinkster) team. This helps mix and match any backend with any other frontend without conflicts.

> [Full API Spec](https://github.com/gothinkster/realworld/tree/master/api)

More information regarding the project can be found here https://github.com/gothinkster/realworld

----------

# Code overview

## Dependencies

- [laravel-sanctum](https://github.com/laravel/sanctum) - For authentication using JSON Web Tokens
- [laravel-cors](https://github.com/barryvdh/laravel-cors) - For handling Cross-Origin Resource Sharing (CORS)
- [laravel-saloon](https://github.com/saloonphp/saloon) - For handling third party APIs integration

## Folders

- `app/Integrations/NewsApi` - Contains the files integrating News api content
- `app/Integrations/NewYorkTimes` - Contains the files integrating New York Times content
- `app/Integrations/TheGuardian` - Contains the files integrating The Guardian content
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `routes` - Contains all the api routes defined in api.php file

## Environment variables

The `.env` file is where you can set the configuration and environment variables for the application. Here are some essential variables you might need to configure:

- `APP_ENV`: The application environment (e.g., `local`, `production`).
- `APP_KEY`: The application key used for encryption and security.
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Database configuration for your chosen database.
- `CORS_ALLOWED_ORIGINS`: Origins allowed for Cross-Origin Resource Sharing (CORS).
- `NEWSAPI_KEY`: The news api key used for fetching articles from news api.
- `NEWSAPI_URL`: The base url of news api.
- `THEGUARDIAN_KEY`: The news api key used for fetching articles from the guardian.
- `THEGUARDIAN_URL`: The base url of the guardian.
- `NYT_KEY`: The news api key used for fetching articles from new york times.
- `NYT_URL`: The base url of new york times.

***Note***: Before running migrations or starting the server, ensure the database connection and other necessary variables are correctly set in the `.env` file.

----------

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api

----------

# Cross-Origin Resource Sharing (CORS)
 
This applications has CORS enabled by default on all API endpoints. The default configuration allows requests from `http://localhost:3000` and `http://localhost:4200` to help speed up your frontend testing. The CORS allowed origins can be changed by setting them in the config file. Please check the following sources to learn more about CORS.
 
- https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
- https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
- https://www.w3.org/TR/cors
