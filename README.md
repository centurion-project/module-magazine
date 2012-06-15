# DOCUMENTATION DU MODULE MAGAZINE

!!! BE ADVICE !!!  
!!! This module is currently in development, and is not entirely working. !!!


________________________________________________________________________________________________________________________
## [Multi-magazine]
 Table : magazine_magazine  
 Possibilité de créer plusieurs magazine  
 Toutes les requêtes doivent être filtré par magazine (magazine_id = {current_magazine})

 L'url d'accès au magazine est http://(www.)host.tld/:magazine-slug


________________________________________________________________________________________________________________________
## [Navigation]
La navigation est composé de l'ensemble des catégories/sous-catégories, elles sont retournées par la méthode getAllCategory()


________________________________________________________________________________________________________________________
## [Home + Home catégorie]
La home est composé d'un système de Highlight décomposé en plusieurs parties :

 * Système de push (colonne verticale)
 * Slider + double push avec rémonté automatique et un système de surcharge par l'utilisateur

Push :

 * Dossier : Remonte le dernier dossier publié mais il est possible à l'utilisateur de surcharger avec un dossier selectionné.
 * Pub/Produit : bloc éditable


________________________________________________________________________________________________________________________
## [Magazine_Model_Article]
Table : magazine_article
TODO: schéma table + relation
TODO: Explication différence headline/article

--------------------------------------------------------------------------------------------------------------
### - Magazine_Model_Article::getHighlighted()
Méthode qui n'a pas de paramètre et qui retourne les 5 dernières articles ajouté au magazine courant.
En cas de surcharge le tableau de sortie est modifié avant d'être retourné par la méthode.

Exemple d'usage :
 * Cas normal
 * Cas surcharge

--------------------------------------------------------------------------------------------------------------
### Magazine_Model_Article::getAllArticle($categoryId, $excludeIdArray)
Cette méthode prend comme paramètre une catégorie, ainsi qu'un tableau d'id à exclure. Ces deux paramètres sont optionnelles.
Si la catégorie n'est pas renseigné, les articles appartenant de l'ensemble des catégories sont récupérer.
Le classement se fait par ordre décroissant sur la date.

--------------------------------------------------------------------------------------------------------------
### Magazine_Model_Article::getAllHeadline($categoryId, $excludeIdArray)
Même fonctionnement que getAllArticle(), sauf retourne des dossiers.


________________________________________________________________________________________________________________________
## [Magazine_Model_Category]
Table : magazine_category
TODO: schéma table + relation

--------------------------------------------------------------------------------------------------------------
### Magazine_Model_Category::getAllCategory($returnSubCategory = false, $order = false)
Par defaut, cette méthode ne retourne que les catégories primaires ayant été attachées à au moins un article, si le
paramètre returnSubCategory est à true, les sous catégories sont aussi retournées.
Le paramètre "order" permet de classer les catégories par rapport à la colonne "order", le classement par defaut étant alphabétique.

________________________________________________________________________________________________________________________
## [Magazine_View_Helper_DisplayPush]

Helper de vue facilitant l'affichage d'un push.

 * displayPush('{nomdupush}')
 


#TODO

 * Gestion du type d'article avec le template associé
 * BO (après la mise à jours du CRUD avec multi-pk)
 * Tests unitaires
 * Ajout du contexte par magazine dans le BO
 * Gestion du multi-catégorie/catégorie principale pour une article (voir du côté du multiselect avec ordre)
 * Gestion du multi-magazine/magazine principal pour une article (voir du côté du multiselect avec ordre)
 * Canonical pour le multi-catégorie et le multi-magazine
