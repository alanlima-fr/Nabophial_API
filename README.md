Première chose à faire quand on clone le repository :
```
composer install
```
Si cette commande échoue 

Ensuite vérifier que PHP est bien installé sur la machine
```
php -v
```
Si ce n'est pas le cas (sur windows), installer Wamp ou Xamp ou équivalent qui vous fournit php et ajouter dans les variables d'environnements php.

---

Ensuite la création d'entité :
```
php bin/console make:entity
```
Attention, l'attribut ID est automatiquement créer par symofny.
par défaut, l'ID 