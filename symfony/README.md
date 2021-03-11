# Création du projet
    composer create-project symfony/website-skeleton MyProject

# Démarrage du serveur local 
    php -S localhost:8000 -t public

# Configuration des environnements
    .env.local.dev
    .env.test.local

## Contenu des fichiers .env.{env}.local
    DATABASE_URL=mysql://root@127.0.0.1:3306/database_name

---
# Création du fichier MakeFile
php bin/console doctrine:database:create

...
# Création des fictures
    composer require orm-fixtures --dev
    php bin/console make:fixtures    
    php bin/console doctrine:fixtures:load   

---
# Configuration du projet
## Configuration de la langue
    config/packages/translation.yaml



---
# Création du module user
    php bin/console make:user
    php bin/console make:auth
    php bin/console make:registration-form
    php bin/console make:reset-password

## Modules user optionnels 
### Vérification par email
    composer require symfonycasts/verify-email-bundle

## Configuration du fichier config/packages/security.yaml
...

---
# Front-end

## Création du controller principal
    php bin/console make:controller Default

## Installation de Webpack Encore
    composer require symfony/webpack-encore-bundle
    yarn install

## Installation de Bootstrap
    yarn add bootstrap --dev

## Installation de FontAwesome
    yarn add --dev @fortawesome/fontawesome-free

---
# Front : configuration de webpack 
## Importation de bootstrap et fontawesome

---
# Configuration pour le déploiement

## Installation de Apache-pack
    composer require symfony/apache-pack

## Générer le fichier d'environnement 
    composer dump-env prod

## Build des assets Webpack Encore
    yarn run encore production

--- 
# Back-end 
## Installation de EasyAdmin
    composer require easycorp/easyadmin-bundle
### Configuration de EasyAdmin

---
# Git : push des fichiers sur git
    git init
    git add .
    git commit -m "initial commit"
    git remote add origin https://github.com/username/nom_depot.git
    git push origin master
