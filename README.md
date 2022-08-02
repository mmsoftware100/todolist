## Todo List api with Lumen for testing purpose.

Testing Purpose  Api , Started on 30 July.

### Setup Guide

- clone this repo
- run `composer install` in project root directory
- run `php artisan migrate` for database migration
- run `php artisan db:seed` for database seeding
- run `php -S localhost:8000 -t public`
- open `http://127.0.0.1:8000/login` and after that login with following credential ( email is user@mail.com and password is password).


### Install Composer

curl -sS <https://getcomposer.org/installer> | sudo php -- --install-dir=/usr/bin --filename=composer

### Setup command sheet

- `php artisan migrate:fresh`
- `php artisan db:seed`
- `php -S localhost:8000 -t public`

### Setup time zone
- `change APP_TIMEZONE=UTC to APP_TIMEZONE=Asia/Yangon in .env`
- `php artisan optimize`

### Dev Guide

- Nothing to do

### Contributors

- **[Hein Aung Htet](https://github.com/Heinaunghtet/)**
- **[Aung Ko Man](https://github.com/Aungkoman)**


### License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


Now you can use postman collection to test API.

**[Postman Documentation for Todo List](https://documenter.getpostman.com/view/11673177/UzdwWnHW)**


