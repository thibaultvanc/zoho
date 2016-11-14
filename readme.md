# Zoho Laravel Package

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)



phpunit --test-suffix ContactTest.php
phpunit --filter it_fetch_the_invoices_for_a_given_contact

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


//////////
POur ajouter des Snippets de class (ex==>create invoice)
1 - /tests/TestCase.php

    public function zoho()
    {
      return new ZohoSnippets;
    }


2 - creer une classe Snippets dans packages/Test nommmee Snippets

3 - //utiliser :
    Parent::zoho()->createContact()
