# Installation

## prérequis

### Backend

* Php : >= 8.2
* YiiFramework >= 2.0

### Front

* Nodejs :v24.8.0
* Nmp :11.6.0

## Installation via Composer

``
composer require dghyse\fractal-cms
``

### build dist

### init node modules

```
npm install
```

#### In dev

```
npm run watch
```

#### For production

```
npm run dist-clean
```

## Base de données

### Exemple Mariadb (MySql)

``
 create database baseName  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
``

### Init database

``
php yii.php migrate
``

[Accueil](01-installation.md) | [Suivant ➡>](02-initialisation.md)