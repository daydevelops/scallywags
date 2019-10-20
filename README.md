# Scally Wags [![Build Status](https://travis-ci.org/daydevelops/scallywags.svg?branch=master)](https://travis-ci.org/daydevelops/scallywags)

A public forum built by [Adam Day](daydevelops.com)

This project was built with Laravel and VueJS. It started as only one feature of a larger project [RballNL](https://www.github.com/daydevelops/RballNL), but was later extracted into its own project.

### Setup Instructions

- clone the repository
- run `cp .env.example .env` and edit your environmental variables
- run `composer install`
- run `npm install`
- run `php artisan key:generate`
- You will also need to set up a Google Recaptcha service. Enter your keys in the .env file.
- This application uses a Redis server. To install Redis, run `sudo apt install redis-server`
- Create a table in your database for the forum
- Run your migrations and seeders (if necessary)

[here](https://scallywags.daydevelops.com) is a live demo of the site
