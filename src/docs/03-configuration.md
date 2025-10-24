# Configuration

## Gestion des paramètres

Nous avons parfois besoin de récupérer des identifiants, des valeurs, des informations etc...
La gestion des paramètres est utile dans ces cas précis où nous devons indiquer une valeur qui pourrait évoluer
selon l'environnement dans lequel nous sommes.

Exemple : Identifiant technique différent entre la **production** et la **pré-producion**.

### Interface

![Gestion des paramètres](./images/parametre_interface.png)

### Editer / Ajouter

L'édition d'un article se réalise en cliquant sur le stylet de la ligne.
La création se réalise en cliquant sur le bouton 'Ajouter'.

### Formulaire de création

![Formulaire de création](./images/parametre_creer.png)

* Groupe : Groupe principal du paramètre
* Nom : Nom du paramètre
* Valeur : Valeur du paramètre (string ou integer) ...

#### Exemple : Création d'un paramètre contenant l'_id de la configuration de l'élément entête_

![Paramètre item_entete](./images/parametre_creation_entete.png)

Pour récupérer cette valeur dans votre code, une fonction existe dans **fractalCms\helpers\Cms**.

#### Exemple de recherche du premier élément **entete** dans un **article**

``` 
    public function actionIndex()
    {
        try {
            Yii::debug('Trace :'.__METHOD__, __METHOD__);
            $content = $this->getContent();
            //Recherche du premier élément "entete" du "Content"
            $itemEntete = $content->getItems()
                ->andWhere(['configItemId' => Cms::getParameter('ITEM', 'ENTETE')])
                ->one();
            return $this->render('index',
                [
                    'content' => $content,
                    'entete' => $itemEntete,
                    ]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
```

## Gestion de la configuration des éléménts

Tous les articles peuvent avoir des éléments. Ces éléments permettent de définir les informations
qui seront utilisées pour générer le HTML finale.

Chaque élément doit-être configuré avant de pouvoir être visible dans l'article.

### Interface

![Gestion de la configuration des éléments](./images/item_interface.png)

### Editer / Ajouter

Afin d'ajouter une nouvelle configuration d'un élément, il faut cliquer sur **Ajouter**.

![Formulaire de création](./images/item_formulaire.png)

* Nom : nom de la configuration, cette valeur doit-être unique
* Configuration Json : Ajout des attributs et leur définition qui sera utiliser pour générer
  le HTML de l'élément dans l'article et définir les attributs à utiliser voir [JsonEditor](https://github.com/josdejong/jsoneditor)

#### paramétrage d'un attribut

Chaque attribut doit comporter au moins ces paramètres pour être utilisable.

* Son nom : nom de l'objet intitulé de l'attribut dans model final
* Type : type de l'attribut
    * "**string**" : Champ input type text
    * "**text**" : Champ input type textarea
    * "**file**" : champ input type file
    * "**radio**" : champ input type radio
    * "**checkbox**" : champ input type checkbox
    * "**wysiwyg**" : champ input type text avec une interface _wysiwyg_ (https://quilljs.com/)
    * "**listcms**" : champ input dropdown list contenant les articles de FractalCMS ainsi que les _controller/action_
*
* Title : intitulé à afficher dans l'article

```json
{
  "title": {
      "type": "string",
      "title" : "Titre de la section"
  }
}
```

### Exemple réel

#### Création d'un élémént _entête_

![Création d'une configuration](./images/item_creation.png)

```json
{
  "title": {
    "type": "string",
    "title": "Titre"
  },
  "subtitle": {
    "type": "string",
    "title": "Sous-titre"
  },
  "description": {
    "type": "wysiwyg",
    "title": "Description"
  },
  "banner": {
    "type": "file",
    "title": "image en 1200x250",
    "accept": "png, jpeg, jpg"
  },
  "alt": {
    "type": "string",
    "title": "Alt de l'image"
  }
}
```

#### Utilisation dans l'interface de mise à jour d'un article

**Choix et ajout de la configuration _entete_**

![Item choix entête](./images/item_entete_ajout.png)

**Ajout de la l'élément dans l'article**

![Item ajout entête](./images/item_entet_ajout.png)

Désormais, l'élément peut-être configuré et enregistré. les informations pourront ête utilisées
sur le _front_.

## Gestion des types d'article

Le configuration du type d'élément faite partie des concepts important de FractalCMS. C'est grâce à cette configuration qu'un
article (Content) pourra être dirigé vers le bon **Controller** et la bonne  **Action** et permettre
ainsi de construire une vue adapté à vos besoin.

### Interface

![Configuration du type d'élément](./images/interface_config_element.png)

### Principe

Dans votre application Yii, ajouté un **Controller** qui étant **fractalCms\controllers\CmsController**, dans lequel vous allez
créer l'action désirée ex :

```php
<?php

namespace webapp\controllers;

use fractalCms\controllers\CmsController;
use fractalCms\models\Content;
use Yii;
use Exception;

/**
 * ContentController class
 *
 * @author David Ghyse <dghyse@redcat.fr>
 * @version XXX
 * @package webapp\controllers
 * @since XXX
 */
class ContentController extends CmsController
{

    /**
     * @return \yii\web\Response|string
     * @since XXX
     */
    public function actionIndex()
    {
        try {
            Yii::debug('Trace :'.__METHOD__, __METHOD__);
            /** Content $content **/
            $content = $this->getContent();
            $itemsQuery = $content->getItems();
            return $this->render('index',
                [
                    'content' => $content,
                    'sections' => $sections
                    ]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}

?>
 ```

#### Ajout une configuration

Afin d'ajouter une configuration, il suffit de cliquer sur "Ajouter", le formulaire de création apparait.

![Formulaire de création](./images/config_article_creer.png)

A ce stade, nous voyons apparaitre la liste des types qui ont été ajoutés dans l'application.
Nous allons créé le type **home** que nous allons diriger vers le contrôleur **ContentController** et l'action **actionIndex**.

* ici : **\content\** dans la liste.

### Retour à la liste

Le type **home** est maintenant créé et est visible dans l'interface.

![Liste des types](./images/config_article_list.png)

### Conclusion

Désormais, tout article ou section qui aura le type **home** sera dirigé vers l'action **actionIndex** du
contrôleur **ContentController**.

[<- Précédent](02-initialisation.md) | [Suivant ->](04-interface.md)