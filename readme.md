# Zoho Laravel Package

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).


1 - Creer repertoire ; /Packages/Organit/zoho
2 - terminal : cd Packages/Organit/zoho
3 - terminal composer init
4 - copier le dossier src depuis l'exemple (ici)
5 - custumize
6 - Modifier composer.json (main)
            ...
            "autoload": {
                "classmap": [
                    "database"
                ],
                "psr-4": {
                    "App\\": "app/",
                    "Organit\\Zoho\\"::"Packages/Organit/Zoho"
                }
            },
            ...
7 - composer dump-autoload from main directory
8 - Ajouter le serviceprovider dans config/app : Organit\Zoho\ZohoServiceProvider::class,
    +facade 'Zoho' => Organit\Zoho\ZohoFacade::class,
9 - cd Packages/Organit/Zoho > git init

10 - recuperer l'url de github pour la coller dans packagist
11 - creer le repo sur packagist
        + add webhook

tests - phpunit
12 - add this to phpunit.xml

          ...
          <testsuites>
              <testsuite name="Application Test Suite">
                  <directory suffix="Test.php">./tests</directory>
              </testsuite>

              <testsuite name="Package Test Suite">
                  <directory suffix="Test.php">./Packages/Organit/Zoho/tests</directory>
              </testsuite>

          </testsuites>
          ...
