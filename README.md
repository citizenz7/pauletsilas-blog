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
* clé du site : 
* clé secrète : 

### AssetMapper
* Installer un package JS (exemple avec SplideJS) : `php bin/console importmap:require @splidejs/splide`
* Vérifier les mises à jour éventuelles de tous les packages JS installés : `php bin/console importmap:outdated`
* Vérifier les mises à jour pour un package JS en particulier : `php bin/console importmap:outdated @splidejs/splide`

### Mise en PROD
1. `php bin/console importmap:install` : re-installer les fichiers JS sur un autre serveur
2. `php bin/console asset-map:compile` : compiler les assets dans public à chaque fois qu'il y a un changement de fichier CSS


yield CollectionField::new('images')
   ->setEntryType(ProductImageType::class);

class ProductImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('imageFile', VichImageType::class, [
            'label' => 'Image',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductImage::class,
        ]);
    }
}