# Paul et Silas
Musique chrétienne, louange chrétienne, actualités de la musique à message chrétien, partage de ressources musicales techniques : guitare, piano, etc.

### ENVIRONNEMENT
* Symfony 7
* PHP 8.3
* Composer 2
* AssetMapper

### ADMIN/BackOffice
* Url : /admin

**Comptes Admin**
* Login : citizenz7@protonmail.com
* Password : 72szG7gU6EvR6nQ5

**Comptes Utilisateurs**
* Login : shadrak@protonmail.com
* Password : 66N9DWFu7N52zfku

* Login : tornzen@protonmail.com
* Password : Jap2DG8Lwc737T2e

### BUNDLES
* easycorp/easyadmin-bundle
* friendsofsymfony/ckeditor-bundle (Installer les assets : `php bin/console assets:install public`)
* symfony/asset-mapper
* knplabs/knp-paginator-bundle
* twig/cssinliner-extra
* twig/extra-bundle
* victor-prdh/recaptcha-bundle
* ...

### Fonts
* Poppins
* Rubik

### COMMANDES
* commande Symfony pour la création d'un admin :
`php bin/console app:create-admin EMAIL PASSWORD FIRSTNAME LASTNAME`

* commande Symfony pour la création d'un utilisateur :
`php bin/console app:create-user EMAIL PASSWORD FIRSTNAME LASTNAME`

### Listener (EventSubscriber is deprecated)
`symfony console make:listener EasyAdminSubscriberOfficeCreatedAt`

### Translations
`php bin/console translation:extract --force fr`

### Créer un mot de passe hashé en console
`symfony console security:hash-password`

### Google reCapctha
* clé du site : 6LcCbxgqAAAAAFOBKnP7BbTq1mujOpDysmpQI2do
* clé secrète : 6LcCbxgqAAAAAOz2c99mxbUpCGdnU5p5nsrCMGhM

### AssetMapper
* Installer un package JS (exemple avec SplideJS) : `php bin/console importmap:require @splidejs/splide`
* Vérifier les mises à jour éventuelles de tous les packages JS installés : `php bin/console importmap:outdated`
* Vérifier les mises à jour pour un package JS en particulier : `php bin/console importmap:outdated @splidejs/splide`

### A FAIRE / VERIFIER AVANT LA MISE EN PROD
* ~~page login CSS~~
* ~~Erreurs personnalisées~~
* ~~reCaptcha Google~~
* ~~test formulaire de contact~~
* ~~BO css~~
* ~~Sitemap~~
* ~~robots.txt~~
* ~~Favicon~~
* ~~Meta + données structurées schema.org~~
* ~~Tarteaucitron~~
* div dans les crudcontroller ADMIN

* Responsive
* SEO :
    * titre H1 de chaque page
    * vérifier les balise HTML de titre sur chaque page : h1 (une seule par page) puis h2, h3, ... (Chrome = Web developer / View document outline)
    * vérifier les img alt=""
* Mentions légales
* flash messages

### Mise en PROD
**Installer/compiler les assets**
1. `php bin/console importmap:install` : re-installer les fichiers JS sur un autre serveur
2. `php bin/console asset-map:compile` : compiler les assets dans public à chaque fois qu'il y a un changement de fichier CSS

**robots.txt**
* Ajouter sitemap dans robots.txt : `Sitemap: https://www.monsite.fr/sitemap.xml`

**Analytics (prod)**
* Google Analytics 4
* Google Search Console + soumission sitemap