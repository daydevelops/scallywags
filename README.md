# Scally Wags

A public forum built by [Adam Day](daydevelops.com)

This project was built with Laravel and VueJS. It started as only one feature of a larger project [RballNL](https://www.github.com/daydevelops/RballNL), but was later extracted into its own project.

### Setup Instructions

- clone the repository
- run `cp .env.example .env` and edit your environmental variables
- run `composer install`
- run `npm install`
- run `php artisan key:generate`
- You will also need to set up a Google Recaptcha service. Enter your secret key in the .env file. Enter your public key in the recaptcha form located in 'resources/views/forum/new.blade.php'.
