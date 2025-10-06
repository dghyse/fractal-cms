# Initialisation

## prérequis

### Backend

* Php : >= 8.2
* YiiFramework >= 2.0

### Front

* Nodejs :v24.8.0
* Nmp :11.6.0

## Composer

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

## Init FractalCMS

### Create Rbac (create role and permission)

``
php yii.php fractalCms:rbac/index
``

### Create Admin (create first admin)
``
php yii.php fractalCms:admin/create
``
### INIT content (create initial content)

``
php yii.php fractalCms:init/index
``

## Config application

### Add module fractal-cms in config file

```php 
    'bootstrap' => [
        'fractal-cms',
        //../..
    ],
    'modules' => [
        'fractal-cms' => [
            'class' => FractalCmsModule::class
        ],
        //../..
    ],
```