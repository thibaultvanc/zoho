

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
8 Ajouter le serviceprovider dans config/app : Organit\Zoho\ZohoServiceProvider::class,
9 - cd Packages/Organit/Zoho > git init

10 - recuperer l'url de github pour la coller dans packagist
