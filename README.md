## ÉVALUATION EN COURS DE FORMATION

### Graduate Développeur

#### LE PROJET Garage automobile

site: [Garage V. Parrot](https://makarovdimitri.shop)

site: [CMS du site Garage V. Parrot](https://cryptic-island-77465-7670ca5f9230.herokuapp.com)

[Trello -- Garage V. Parrot](https://trello.com/b/saHxK2OJ/garage-v-parrot)

[Figma du projet](https://www.figma.com/file/C9Q6iNw5DtFr0hKYcW6ydy/Garage-V.-Parrot?type=design&mode=design&t=NPMIASVXXPmewEdz-1)

[Prototypes Figma du projet](https://www.figma.com/proto/C9Q6iNw5DtFr0hKYcW6ydy/Garage-V.-Parrot?type=design&t=GQ78AuK9QHTX6Ezx-1&scaling=min-zoom&page-id=0%3A1&node-id=3-27&starting-point-node-id=3%3A27&show-proto-sidebar=1&mode=design)

[Diagramme de classe dbdiagram.io](https://dbdiagram.io/d/Garage-V-Parrot-65d280b5ac844320ae6b6c22)

#### Spécifications techniques

##### Serveur :

* AWS (Amazon Web Services) RDS (base de données MySQL)
* AWS (Amazon Web Services) S3 bucket (stockage des images)
* Extension PHP : PDO
* MySQL Community
* Heroku.com

##### Pour le front :

* HTML 5
* CSS 3
* Bootstrap 5
* JavaScript

##### Pour le back :

* PHP 8.2 sous PDO
* MySQL Community
* AWS S3 SDK

##### Pour les diagrammes :

* Plant UML
* DBML (Database Markup Language)


### Manuel d'utilisation :

#### Premiere connexion :

Lors de la première connexion sur le Système de Gestion du Contenu il faut rentrer : 

* le mail : admin@admin.com

* le mot de passe : admin

Puis, dans le menu (sur la page) de gestion des utilisateurs il faut créer un utilisateur admin de votre choix et supprimer l’admin default.


#### Utilisation du “dashboard” : 

###### L'admin a accès au fonctionnalités suivantes : 

* Gestion d’utilisateurs 

* Gestion des voitures d’occasion 

* Gestion des messages 

* Gestion des services 

* Gestion des commentaires 

* Gestion d’horaires d’ouverture 

###### L'employé a accès au fonctionnalités suivantes : 

* Gestion des voitures d’occasion 

* Gestion des messages 

* Gestion des commentaires 

#### La gestion des horaires d’ouverture :

* pour ouvrir un jour donné il faut: choisir “<span style="color:green">ouvrir</span>” pour la journée, choisir “<span style="color:green">ouvrir</span>” pour le matin, le soir ou les deux, puis sauvegarder les modifications. Après cette étape, vous pouvez choisir les heures. 

* Pour <span style="color:red">fermer</span>: <span style="color:red">fermer</span> les deux horaires – matin et soir, <span style="color:red">fermer</span> la journée, sauvegarder.