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

