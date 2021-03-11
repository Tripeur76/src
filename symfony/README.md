# Création du projet
composer create-project symfony/website-skeleton MyProject

# configuration des environnements
    .env.local.dev
    .env.test.local


# Création du module user
php bin/console make:user
php bin/console make:auth
php bin/console make:registration-form
php bin/console make:reset-password

## Modules user optionnels 
### Vérification par email
composer require symfonycasts/verify-email-bundle



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
## importation de bootstrap et fontawesome

---
# Configuration pour le déploiement

## Installation de Apache-pack
composer require symfony/apache-pack

## Générer le fichier d'environnement 
composer dump-env prod

## Build des assets Webpack Encore
yarn run encore production

---
# Git : push des fichiers sur git
git init
git add .
git commit -m "initial commit"
git remote add origin https://github.com/username/nom_depot.git
git push origin master
