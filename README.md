# TP_Symfony_Projet_Photography
Projet libre du 17/01/2025 au 26/01/2025
Images utilisées : Mes photos.

# Si problème d'accès à la bdd :
Supprimer le/les fichier(s) de migration(s), puis faire :
- cconsole make:migration
- cconsole d:m:m

# Importer la DataFixtures:
- cconsole d:f:l

# Accéder au dashboard :

## Installer intl (pour lire les datetime)
- nnpm
- apt-get update
- apt-get install -y libicu-dev
- docker-php-ext-install intl
- service apache2 restart
## Vérifier si intl est installé
- php -m | grep intl
si installé l'on verra : intl
<br>
*Vous pouvez aller voir /admin/dashboard*

# Lancer Watch
- nnpm
- npm run watch

## Installation de VichUploader pour téléverser des images
- ccomposer require vich/uploader-bundleservice