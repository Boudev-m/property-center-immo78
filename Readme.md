## Immo78
  
### Objectif
Créer un site web pour l'agence immobilière Immo78 située dans les Yvelines et proposant des biens immobiliers à la vente. Ce qui permettra à l'agence d'accroître sa visibilité dans la région et d'augmenter le nombre d'acheteurs potentiels.

### Fonctionnalités globales
- Afficher les biens en vente et leurs informations
- Rechercher un bien selon des critères
- Contacter l'agence via un formulaire
- Afficher les articles d'actualité immobilière
- Réagir à un article en postant un commentaire
- Connexion et authentification administrateur
- Gestions des biens et des articles d'actualité (back-office)

### Technos utilisées
```Symfony``` ```MySQL``` ```Bootstrap``` ```Javascript```

### Environnement de développement
```Windows``` ```Visual Studio Code``` ```WAMP```

### Installation et exécution de l'application
Prérequis : ``Symfony CLI`` ``MySQL`` ``NPM``  
Notes : Le gestionnaire Composer est inclus dans Symfony CLI.  
Vous pouvez installer WAMP pour Windows et avoir accès à phpmyadmin pour gérer des bases de données MySQL.  
Pour avoir NPM, il faut installer NodeJs.  

Suivre les étapes suivantes :  
- Ouvrir un terminal et cloner le depôt avec la commande ``git clone https://github.com/BouiMust/property-center-immo78``

- Se déplacer à la racine du dossier ``cd property-center-immo78`` puis créer le fichier .env à partir du fichier d'exemple ``cp .env.example .env``. Dans ce fichier .env, mettre le lien url vers votre base de données et le lien vers votre serveur Smtp.

- Créer votre base de données en important le fichier database.sql dans MySQL

- Installer les dépendances du projet avec la commande ``symfony composer install``

- Installer les dépendances NPM avec ``npm install`` 

- Lancer l'application en utilisant le serveur Symfony avec  ``symfony serve``

- Ouvrir un 2ème terminal. Lancer le serveur node et compiler les assets (fichiers JS et CSS) avec ``npm run dev-server``

*Le site sera accessible à l'adresse ``https://localhost:8000/``*
    
### Composition du projet
``-``
  
### Dépendances liées a l'application
``cocur/slugify`` : pour créer des urls clean  
``fakerphp/faker`` : générer des données factices  
``knplabs/knp-paginator-bundle`` : lister les biens sur plusieurs pages  
``vich/uploader-bundle`` : gérer les images importées  
``liip/imagine-bundle`` : redimensionner les images et les mettre en cache  
``symfony/webpack-encore-bundle`` : intégrer webpack qui sert à gérer et compiler les assets (Js,Css,...)  
``beberlei/doctrineextensions=dev-master`` : ajouter des fonctions DQL à Doctrine (Sin,Cos,Pi,...)  
``twig/cache-extra`` : mettre des templates twig en cache  
``twig/string-extra`` : accéder à des filtres supplémentaire dans les templates twig  
  
``@placekit/autocomplete-js`` : Recherche et autocomplétion d'adresses en utilisant l'API placekit (adresses,code postal,ville,latitude,longitude)  
``leaflet`` : création et personnalisation de maps  

### Entités en base de données
- ``Property`` : contient les biens
- ``Option`` : contient les options des biens (avec une relation ManyToMany)
- ``Picture`` : contient les images des biens (relation OneToMany)
- ``News`` : contient les articles d'actualité
- ``Post`` : contient les posts utilisateurs associés aux articles d'actualité (relation OneToMany)
- ``User`` : contient le compte administrateur

### Routes et Urls
    
| Route                   | Url associé                   | Description  |
| ----------------------- |------------------------------ | :-----------:|
| _home_                  | /, /home, /index              | Page d'accueil |
| _contact_               | /contact                      | Page de contact |
| _property.index_        | /biens                        | Page listant les biens en vente |
| _property.show_         | /biens/{id}-{slug}            | Page affichant un bien en détail |
| _news.index_            | /actualites                   | Page listant les articles d'actualités |
| _news.show_             | /actualites/{id}-{slug}       | Page affichant un article |
| _login_                 | /login, /admin                | Page de connexion |
| _logout_                | /logout                       | Déconnexion |
| **(Back-office)** |
| _admin.property.index_  | /admin/biens                  | Page listant tous les biens (vendus ou non) |
| _admin.property.new_    | /admin/biens/{id}/editer      | Page de création d'un bien |
| _admin.property.edit_   | /admin/biens/{id}/editer      | Page de modification d'un bien |
| _admin.property.delete_ | /admin/biens/{id}/supprimer   | Suppresion d'un bien |
| _admin.option.index_    | /admin/options                | Page listant tous les options |
| _admin.option.new_      | /admin/options/nouveau        | Page de création d'une option |
| _admin.option.edit_     | /admin/options/{id}/editer    | Page de modification d'une option |
| _admin.option.delete_   | /admin/options/{id}/supprimer | Page de suppresion d'une option |
| _admin.news.index_      | /admin/actualites             | Page listant tous les articles d'actualités |
| _admin.news.new_        | /admin/actualites/nouveau     | Page de création d'un article |
| _admin.news.edit_       | /admin/actualites/{id}/editer   | Page de modification d'un article |
| _admin.news.delete_     | /admin/actualites/{id}/supprimer| Page de suppression d'un article |
| _admin.picture.delete_  | /admin/images/{id}/supprimer    | Suppression d'une image |

*{id} : identifiant de l'objet*  
*{slug} : titre de l'objet remanié pour créer une url propre et mieux référencé*

### Sécurité

- Contrôle et validation des saisies utilisateurs (via les contraintes proposés par l'ORM Doctrine)
- Authentification du rôle administrateur pour l'accès au back-office
- Authentification par token CSRF pour les opérations sensibles
- Hachage des mots de passe
- Installation du certificat SSL (en local) pour sécuriser les connexions HTTPS (entre navigateur et serveur)

### Identifiants Admin

- Nom : admin
- Mot de passe : admin729

### Bugs à corriger
#1 : Dans la page de création/modifiation d'un bien, l'upload d'un fichier image autre que JPG ou PNG provoque une erreur avec le bundle Liip.  
``Liip\ImagineBundle\Templating\LazyFilterRuntime::filter(): Argument #1 ($path) must be of type string, null given, called in C:\wamp64\www\Immo78\var\cache\dev\twig\a5\a528cdb7dfadd924c40aadb124a80da9.php on line 104``  
Comportement attendu : retourner sur le formulaire et afficher un message d'erreur 'Format d'image invalide. Format(s) accepté(s) : JPG, PNG'

### Axes d'amélioration
- design responsiv pour les petits écrans
- mettre le systeme de recherche de biens en page d'accueil
- ajouter un bouton pour réinitialiser le filtre de recherche
- ajouter la possibilité de créer un autre compte utilisateur ou modifier un compte existant
- afficher les resultats des biens pendant la recherche par filtre, sans actualiser la page, en temps réel (avec JS)
- ajouter la gestion des posts
- ajouter la possibilité de commenter un post (= création d'une nouvelle entité)
- créer des tests pour chaque fonctionnalité

### Rendu visuel

##### Accueil :  
<img src="https://i.ibb.co/DW0wcCT/home.webp" width="500px" loading="lazy">

##### Les biens :  
<img src="https://i.ibb.co/KNVjQfN/index-property.webp" width="500px" loading="lazy">

##### Un bien :  
<img src="https://i.ibb.co/CzdXzR5/show-property.webp" width="500px" loading="lazy">

##### Contact :  
<img src="https://i.ibb.co/30PZvB1/contact.webp" width="500px" loading="lazy">

##### L'actualité :  
<img src="https://i.ibb.co/1qbLcZX/index-news.webp" width="500px" loading="lazy">

##### Un article d'actu :  
<img src="https://i.ibb.co/stdtVmD/show-news.webp" width="500px" loading="lazy">

##### Gérer les biens :
<img src="https://i.ibb.co/jMgPsRN/admin-index-property.webp" width="500px" loading="lazy">

##### Editer un bien :
<img src="https://i.ibb.co/hfKLfKf/admin-edit-property.webp" width="500px" loading="lazy">