# FractalCMS

FractalCMS est un CMS léger et modulaire conçu pour gérer du contenu hiérarchisé de manière flexible et performante.
Son principe fondateur repose sur une arborescence fractionnelle, permettant de représenter et manipuler des contenus imbriqués à profondeur illimitée, tout en gardant une structure simple et interrogeable en SQL.

## 🌱 Philosophie

* Simplicité : une seule table pour les contenus, une clé fractionnelle, et un schéma clair.
* Flexibilité : chaque élément peut être une section, un article ou un sous-contenu, sans limite de profondeur.
* Performance : les requêtes SQL restent lisibles et rapides (ex. récupération d’une section et de ses enfants directs ou indirects).
* Évolutivité : conçu pour être facilement étendu via API RESTful, avec une intégration front (par ex. Aurelia, Vue, React) naturelle.

## 🚀 Objectifs

FractalCMS n’a pas vocation à concurrencer les solutions existantes comme WordPress ou Drupal.
Il s’agit avant tout d’un projet personnel, pensé comme un terrain d’expérimentation pour :

* tester des idées d’architecture,
* conserver la main sur les choix techniques,
* et disposer d’un outil léger, adapté à un blog, site perso, portfolio développeur.

## 🔧 Stack utilisée

* Backend : PHP + MySQL / MariaDb
* Yii2
* Frontend : Aurelia 2 + BootstrapCSS
* Éditeur : JSONEditor / QuillJS pour la gestion des contenus
* Accessibilité : Gestion du SEO

## Technologies utilisées

FractalCMS repose sur plusieurs briques open-source modernes :

- [YiiFramework 2.0](https://www.yiiframework.com/) : base du backend PHP
- [Aurelia 2](https://aurelia.io/) : framework JavaScript pour le front-end et l’interface d’administration
- [QuillJS](https://quilljs.com/) : éditeur WYSIWYG pour la création et la mise en forme de contenus
- [JSONEditor](https://github.com/josdejong/jsoneditor) : interface de gestion et visualisation des données JSON
- [Bootstrap 5](https://getbootstrap.com/) : composants et styles de base (via asset-packagist)
- [MySQL](https://www.mysql.com/fr/) : Base de données
- [MariaDb](https://mariadb.org/): Base de données
### Documentation

* Voir la [Documentation](src/docs/index.md)
* 
## Exemple d’utilisation

Vous voulez un site fonctionnel prêt en quelques minutes ?  
Consultez le preset **Blog** basé sur FractalCMS : [dghyse/fractal-cms-blog](https://github.com/dghyse/blog-fractal-cms)

Ce dépôt contient un blog clé en main :
- Installation rapide
- Articles et menus déjà créés
- Documentation intégrée

## Licence

Ce projet est distribué sous la licence MIT.  
Voir le fichier [LICENSE](LICENSE) pour plus d’informations.
