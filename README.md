# FractalCMS

FractalCMS est un CMS léger et modulaire conçu pour gérer du contenu hiérarchisé de manière flexible et performante.
Son principe fondateur repose sur une arborescence fractionnelle, permettant de représenter et manipuler des contenus imbriqués à profondeur illimitée, tout en gardant une structure simple et interrogeable en SQL.

## 🌱 Philosophie

* Simplicité : une seule table, une clé fractionnelle, et un schéma clair.
* Flexibilité : chaque élément peut être une section, un article ou un sous-contenu, sans limite de profondeur.
* Performance : les requêtes SQL restent lisibles et rapides (ex. récupération d’une section et de ses enfants directs ou indirects).
* Évolutivité : conçu pour être facilement étendu via API RESTful, avec une intégration front (par ex. Aurelia, Vue, React) naturelle.

## 🚀 Objectifs

FractalCMS n’a pas vocation à concurrencer les solutions existantes comme WordPress ou Drupal.
Il s’agit avant tout d’un projet personnel, pensé comme un terrain d’expérimentation pour :

* tester des idées d’architecture,
* conserver la main sur les choix techniques,
* et disposer d’un outil léger, adapté à un portfolio développeur.

## 🔧 Stack utilisée

* Backend : PHP (API REST) + MySQL
* Frontend : Aurelia 2 + BootstrapCSS
* Éditeur : JSONEditor / QuillJS pour la gestion des contenus
* Accessibilité : Gestion du SEO

# Initialisation

## prérequis

### Backend

* Php : >= 8.2

### Front

* Nodejs :v24.8.0
* Nmp :11.6.0

## Composer

``
comopser require dghyse\fractal-cms
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

### CMS Front

``
https://localhost/fractal-cms
``

## Préparer l'application

Afin de permettre à FractalCms de définir les contrôler/action à attacher aux éléments "Content".
Au moins un contrôler doit être créé qui étend la classe "CmsController"

```php

<?php

use fractalCms\controllers\CmsController;
use Yii;
use Exception;

class ContentController extends CmsController
{
      public function actionIndex()
    {
        try {
            Yii::debug('Trace :'.__METHOD__, __METHOD__);
            /**
             * La fonction public getContent() permet de récupérer le model Content lié à cette action du controller
             */
            $content = $this->getContent();
    
            /**
            * On récupére la Query de items du model Content
            */
            $itemsQuery = $content->getItems();
            
            /** on envoi tout à la vue **/
            return $this->render('index',
                [
                    'content' => $content,
                    'itemsQuery' => $itemsQuery
                    ]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}

```
