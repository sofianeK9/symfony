# Symfony

Ce repo contient une application de gestion de formation.
IL s'agit d'un projet pédagogique pour la promo 11.

## Prérequis
    - Linux, MacOs ou Windows 
    - Bash
    - PHP 8
    - composer
    - symfony-cli
    - MariaDB 10
    - docker (optionnel)

## Installation

```
git clone https://github.com/sofianeK9/HTML
cd symfony
composer install
```

Créér une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créez un fichier `.env` à la racine du projet :

```
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=f47df975e5d858e3e113c75eca35e74b
DATABASE_URL="mysql://dba:123@127.0.0.1:3306/symfony?serverVersion=mariadb-10.6.128&charset=utf8mb4"

```

Pensez à adapter la variable `APP_SECRET` et le mot de passe `123` dans la variable `DATABASE_URL`

**Atention : `APP_SECRET` doit etre une chaine de caractére de 32 caractéres de valeurs en hexadecimal.**

## Migration et fixtures

Pour que l'application soit utilisable, vous devez crééz le schéma de base de données et charger les données : 

```
bin/dofilo.sh
```

## Utilisation

Lancer le serveur web de developpement :

```
symfony serve
```

Puis ouvrir la page suivante : [https://local:host:8000](https://local:host:8000)

![mon image]()

## Mentions légales

Ce projet est sous licence MIT.

La licence est disponible ici [LICENCE](LICENCE)
