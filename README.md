# TP_Symfony_Projet_Photography
Projet libre du 17/01/2025 au 26/01/2025

### Accéder au dashboard :

## Installer intl (pour lire les datetime)
- nnpm
- apt-get update
- apt-get install -y libicu-dev
- docker-php-ext-install intl
- service apache2 restart
## Vérifier si intl est installé
- php -m | grep intl
si installé l'on verra : intl
*Vous pouvez aller voir /admin/dashboard*

## Installation de VichUploader pour téléverser des images
- ccomposer require vich/uploader-bundleservice apache2 restart