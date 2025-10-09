# Initialisation

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

````php 
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
````

[<- Précédent](01-installation.md) | [Suivant ->](03-configuration.md)