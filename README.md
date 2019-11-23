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
- This application also uses Pusher for the chat rooms. To opt into using Pusher, add your keys to the .env file and change the *BROADCAST_DRIVER* value
- Create a table in your database for the forum
- Run your migrations and seeders (if necessary)
- Compile your assets with `npm run dev`, `npm run prod`, or `npm run watch`, depending on your environment

[here](https://scallywags.daydevelops.com) is a live demo of the site

Anyone who would like to contribute is welcome to do so.