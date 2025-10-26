# Sujets Avancés

## Gestion du menu

Dans FractalCMS, il est possible de créer des menus. c'est menu pourront être ensuite récupéré
sur le site et affiché sur la page.

### Interface

![Gestion des menus](./images/menu_interface.png)

### Editer / Ajouter

L'édition d'un article se réalise en cliquant sur le stylet de la ligne.
La création se réalise en cliquant sur le bouton 'Ajouter'.

### Création d'un menu étape 1

![Créer étape 1](./images/menu_creer_etape1.png)

Lors de l'ajout d'un menu, il est demandé en étape 1 le nom de ce menu.

* actif : Permet d'activer le menu
* Nom : c'est le nom qui permettra de le trouver, cette valeur est **unique**

### Création d'un menu étape 2

Après avoir saisie un nom unique et valider le formulaire. Le formulaire ce met à jour afin
de permettre d'ajouter des **élements du menu**, en cliquant sur le bouton **Ajouter un élément**.

![Créer étape 2](./images/menu_creer_etape2.png)

#### Ajout d'un élément

![Ajouter un élément](./images/menu_creer_un_element.png)

* Nom : Nom de l'élément
* Route CMS : Lien vers un article actif de FractalCMS
* Route locale : Lien vers une action d'un contrôleur hors FractalCMS de votre application Web
* Parent : L'élément créé peut-être un enfant d'un autre élément.

##### Exemple : Ajout élément "accueil"

![Ajouter l'élément accueil](./images/menu_creer_ajout_element_accueil.png)

#### Après plusieurs ajouts

![Menu header](./images/menu_creer_header.png)

#### Récupérer le menu sur votre application Web

Il est possible d'adapter la donnée de retour afin de correspondre à votre logique.

```
public function get($name)
{
    try {
        $result = [];
            $menuId = Cms::getParameter('MENU', $name);
            $menu = Menu::findOne($menuId);
            if ($menu instanceof Menu) {
                $menuItemsQuery = $menu->getMenuItemChild();
                $result = $this->build($menuItemsQuery);
            }
        return $result;
    } catch (Exception $e) {
        Yii::error($e->getMessage(), __METHOD__);
        throw $e;
    }
}

protected function build(ActiveQuery $menuItemsQuery)
{
    try {
        $result  = [];
        /** @var MenuItem $menuItem */
        foreach ($menuItemsQuery->each() as $menuItem) {
            $part = [];
            $contentTarget = $menuItem->getContent()->andWhere(['active' => 1])->one();
            if ($contentTarget instanceof Content || empty($menuItem->route) === false) {
                $route = ($contentTarget !== null) ? $contentTarget->getRoute() : $menuItem->route;
                $part['name'] = $menuItem->name;
                $part['route'] = $route;
                $subMenuQuery = $menuItem->getMenuItems();
                if ($subMenuQuery->count() > 0 ) {
                    $part['child'] = $this->build($menuItem->getMenuItems());
                }
                $result[] = $part;
            }
        }
        return $result;
    } catch (Exception $e) {
        Yii::error($e->getMessage(), __METHOD__);
        throw $e;
    }

}
```

## Personnaliser les vues des éléments de l'article

Dans FractalCMS, nous pouvons personnaliser la vue qui sera utilisée pour générer
le HTML de l'élément dans la partie **Gestion des éléments** du formulaire de création
d'un article.

### Propriété

La propriété public **viewItemPath** du module FractalCMS peut-être valorisé dans le fichier de 
configuration. La propriété est valorisée par défaut par  **@webapp/views/fractal-cms**.

#### exemple

```bash
/* fichier de configuration */
/*../..*/
'bootstrap' => [
    'log',
    'fractal-cms'
],
'modules' => [
    'fractal-cms' => [
        'class' => FractalCmsModule::class,
        'viewItemPath'=> '@webapp/views/fractal-cms'
    ]
],
/*../..*/
```

### Régle de nommage des vues

Le nom du fichier doit correspondre à la valeur de la propriété **name** de la **Configuration de l'élément**. Les noms
comportant des **-** seront automatiquement remplacés par des **_**.

#### Exemples

##### Entête

* Nom : **entete**
* Valeur de la propriété **name** de la configuration : **entete**
* Nom de la vue : **entete.php**

##### Image HTML

* Nom : **image-html**
* Valeur de la propriété **name** de la configuration : **image-html**
* Nom de la vue : **image_html.php**

### Exemple de fichier image_html.php

```php
<?php
/**
 * _sample-item.php
 *
 * PHP Version 8.2+
 *
 * @version XXX
 * @package webapp\views\layouts
 *
 * @var $this yii\web\View
 * @var $model \fractalCms\models\Item
 * @var $content \fractalCms\models\Content
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
use fractalCms\helpers\Cms;
?>
<?php
foreach ($model->configItem->configArray as $attribute => $data):?>
    <div class="col form-group p-0 mt-1">
        <?php
        $title = ($data['title']) ?? '';
        $description = ($data['description']) ?? '';
        $options = ($data['options']) ?? null;
        $accept = ($data['accept']) ?? null;
        switch ($data['type']) {
            case Html::CONFIG_TYPE_FILE:
            case Html::CONFIG_TYPE_FILES:
                echo Html::tag('cms-file-upload', '', [
                    'title.bind' => '\''.$title.'\'',
                    'name' => Html::getInputName($content, 'items['.$model->id.']['.$attribute.']'),
                    'value' => $model->$attribute,
                    'upload-file-text' => 'Ajouter une fichier',
                    'file-type' => $accept
                ]);
                break;
            case Html::CONFIG_TYPE_WYSIWYG:
                echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                echo Html::activeHiddenInput($content, 'items['.$model->id.']['.$attribute.']', ['value' => $model->$attribute, 'class' => 'wysiwygInput']);
                $inputNameId = Html::getInputId($content, 'items['.$model->id.']['.$attribute.']');
                echo Html::tag('div', '',
                    [
                        'cms-wysiwyg-editor' => 'input-id.bind:\''.$inputNameId.'\'',
                    ]);
                break;
        }
        ?>
    </div>

    <?php if (empty($description) === false):?>
        <div class="col p-0">
            <p class="fw-lighter fst-italic">
                <?php echo $description;?>
            </p>
        </div>
    <?php endif;?>
<?php endforeach;?>
```

[<- Précédent](05-content.md) | [Accueil](index.md)