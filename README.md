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
* ~~flash messages~~
* ~~BO css~~
* ~~Sitemap~~
* ~~robots.txt~~
* ~~Favicon~~
* ~~Meta + données structurées schema.org~~
* ~~Tarteaucitron~~
* ~~permissions BO : utilisateurs, commentaires, articles, images, fichiers~~
* ~~div dans les crudcontroller ADMIN~~
* ~~Responsive~~
* SEO :
    * ~~titre H1 de chaque page~~
    * ~~vérifier les balise HTML de titre sur chaque page : h1 (une seule par page) puis h2, h3, ...~~
    * ~~vérifier les img alt=""~~

* ~~CGU~~
* ~~Confidentialite~~
* ~~constraints dans crud controller sur images et fichiers~~
* Mentions légales

* Un utlisateur qui n'aura pas validé son adresse e-mail ne pourra pas proposer d'article.

### Mise en PROD
**Installer/compiler les assets**
1. `php bin/console importmap:install` : re-installer les fichiers JS sur un autre serveur
2. `php bin/console asset-map:compile` : compiler les assets dans public à chaque fois qu'il y a un changement de fichier CSS

**robots.txt**
* Ajouter sitemap dans robots.txt : `Sitemap: https://www.monsite.fr/sitemap.xml`

**Analytics (prod)**
* Google Analytics 4
* Google Search Console + soumission sitemap



### COMMENT CA MARCHE ?

##### Fonctionnalités générales
**Utilisateurs**
Il existe des comptes Admin (tous les droits de création, d'édition et de suppression) et des comptes Users avec certains droits limités.

**Inscription**
Tout le monde peut s'inscrire via le formulaire d'inscription.
A l'inscription, l'utilisateur recevra un mail de confirmation d'adresse e-mail. Un lien dans ce mail lui permettra d'effectuer la validation de son compte.

**Connexion**
Un formulaire de connexion est disponible pour s'authentifier sur le site et acquérir les droits d'utilisation, notamment dans la partie **Tableau de bord (admin)**.

**Mot de passe**
Un utilisateur pourra re-initialiser son mot de passe depuis le lien **Mot de passe oublié ?** présent sur la page de connexion.

**Articles**
Un utilisateur connecté pourra ajouter un article depuis son Tableau de bord. Il pourra aussi modifier et supprimer ses propres articles.
Lors de la création de l'article, celui-ci n'est pas automatiquement publié sur le site. Il devra être "vérifé" par un administrateur qui choisira de le publier... ou non.

**Commentaires**
Un utilisateur connecté pourra ajouter un commentaire directement depuis la page de l'article, grâce au formulaire Commentaire situé en bas d'article.
Le commentaire ne sera pas automatiquement publié sur le site. Il devra être "vérifé" par un administrateur qui choisira de le publier... ou non.

**Formulaire de contact**
Tout le monde peut utiliser le formulaire de contact depuis la page /contact. Le message sera envoyé à l'adresse e-mail du site et sera "vérifé" par un administrateur qui choisira d'y apporter une réponse... ou non.

**Recherche**
Une page /recherche permet de rechercher des articles par mot-clé. Tout le monde pourra utiliser cette fonctionnalité.

##### Tableau de bord
Le tableau de bord est accessible uniquement par un utilisateur connecté depuis le lien **Tableau de bord** qui apparaitra dans le menu général.
Depuis le "Tableau de bord", un utilisateur pourra :
* Modifier/compléter les informations de son profil
* Créer/Modifier/Supprimer un article
* Voir la liste des commentaires des articles
* Voir les catégories d'articles
* Voir éventuellement, selon ses droits, les images des articles
* Voir éventuellement, selon ses droits, les fichiers des articles

1. **Modifier les informations de son profil**
Dans le Tableau de bord / Paramètres / **Mon profil**, un utilisateur pourra cliquer sur la petite icône jaune d'édition. Il pourra :
    * **Onglet Informations**
        * modifier son nom
        * modifier son prénom
        * modifier son adresse e-mail
    * **Onglet Image de profil**
        * modifier son image de profil
            1. cliquer sur la corbeille
            2. choisir une nouvelle image
    * **Onglet Bio**
        * modifier sa biographie (quelques lignes de texte qui seront affichées sous le texte des articles publiés par l'utilisateur). **Obligatoire**.

2. **Créer un article**
Dans le Tableau de bord / Sections / **Articles**, un utilisateur pourra cliquer sur **Ajouter un Article**. Il devra :
    * **Onglet infos générales**
        * entrer un titre (le champ Slug qui correspond à l'adresse finale de l'article est automatiquement remplie)
        * sélectionner au moins une catégorie. Plusieurs choix possibles.
    * **Onglet Textes**
        * entrer une courte **Intro** (2 ou 3 phrases courtes d'introduction qui seront affichées en haut de l'article et sur la page d'accueil)
        * entrer le **Contenu** de l'article (texte long). Un éditeur de texte lui permet de mettre en forme tout ou partie de ce contenu texte. Il peut glisser/déposer des images d'illustration de l'article à l'endroit souhaité dans le Contenu **MAIS** il devra veiller à ce que les images soient libres de droits d'utilisation et que la taille de ces images soit inférieure à 300 Ko.
    * **Onglet Images**
        * sélectionner une image principale **obligatoire** et ajouter sa description courte. Attention aux droits d'utilisation de l'image ainsi qu'a sa taille (300 Ko maximum si possible...).
        * sélectionner une ou plusieurs images pour la **galerie d'images facultative**. Attention aux droits d'utilisation des images ainsi qu'a sa taille (300 Ko maximum si possible...).
    * **Onglet Documents**
        * sélectionner un ou plusieurs **fichiers PDF facultatifs**. Attention au poids des fichiers (1 Mo maximum si possible...).
    * **Onglet SEO**
        Il s'agit de la partie qui va permettre un référencement de l'article sur les moteurs de recherche. Les deux champs de cet onglet sont **obligatoires** :
        * Titre SEO : Le titre affiché dans les moteurs de recherche. Idéal : 55 caractères maxi. Vous pouvez reprendre le titre de l'article et l'adapter si besoin.
        * Description SEO : La description affichée dans les moteurs de recherche. 105 caractères maxi. Il s'agit d'une description en 2 ou 3 phrases qui va mettre en valeur l'idée centrale de l'article. Il est important d'utiliser ici un ou plusieurs mot-clés significatifs de cet article (musique, tablature, guitare, débutant, louange, etc.)
        * concernant le SEO, l'administrateur du site vérifiera régulièrement cette section dans les articles et pourra remplacer, compléter et/ou améliorer les informations entrées.
