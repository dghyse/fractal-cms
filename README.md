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

### Documentation

* Voir la [Documentation](src/docs/documentation.md)