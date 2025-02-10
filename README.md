# Flipping Book (for Laravel)

## About

Flipping Book package allows you to create digital content like e-books, online magazines, products catalogues with page flip, reader-friendly controls and embed them right on your Laravel site.

This Flipping Book package is developed to make your digital publications look realistic, personalized and attract customers’ attention. People can flip pages of e-book, online newspaper, etc., with a finger touch and feel like holding a real one.

Flipping book consists of an administrative part and a public part. The administrative panel consists of 3 parts: categories, publications, pages. The publication page is based on the uploaded page image. Multiple page uploads into one publication from a zip archive are available.

If you want to turn a source file in **PDF format into a Flipping Book**, you can first use any of the many online 
services "pdf to png", and then upload a zip with pictures to the publication with one click.

The public part consists of a list of categories, a list of publications and viewing publications in Flipping Book format.

The [flipbook-vue](https://github.com/ts1/flipbook-vue) and [Vuetify slider](https://vuetifyjs.com/en/components/sliders/) scripts were used to display Flipping Book.

## Installation

```
composer require fsvblr/flippingbook
```
```
php artisan vendor:publish --provider="Flippingbook\FlippingbookServiceProvider"
```
```
php artisan migrate
```

## Installation from github.com

Add to your Laravel project's composer.json file:
```
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/fsvblr/flippingbook"
        }
    ],
"require": {
        ...
        "fsvblr/flippingbook": "dev-master"
    },
```
```
composer update
```
```
php artisan vendor:publish --provider="Flippingbook\FlippingbookServiceProvider"
```
```
php artisan migrate
```

## Please note

- It is assumed that your site has user [authentication](https://laravel.com/docs/authentication). If it does not, then the simplest option is [laravel/ui](https://packagist.org/packages/laravel/ui).
- Don't forget to wrap flippingbook's admin routes with access check.
- Don't forget to create a storage link on your site if you haven't done so yet:
```
php artisan storage:link
```
- The ImageMagick and Zip extensions are dependencies in this project.
- The package is tested on Laravel Framework 11.40.0.

## Demo

Screenshots of the admin and public parts are available in the folder /public/images/demo .
