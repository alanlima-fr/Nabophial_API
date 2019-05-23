# Première chose à faire quand on clone le repository :
```
composer install
```
Si cette commande échoue :

Sous windows => C'est que composer n'est pas installé. Allez sur https://getcomposer.org/download/ pour le télécharger et l'installer. Ensuite redémarrer le terminal/IDE et répéter la commande.

Ensuite vérifier que PHP est bien installé sur la machine
```
php -v
```
Si ce n'est pas le cas (sur windows), installer Wamp ou Xamp ou équivalent qui vous fournit php et ajouter dans les variables d'environnements php.

---

## Ensuite la création d'entité :
```
php bin/console make:entity
```
Attention, l'attribut ID est automatiquement créer par symofny.
par défaut, l'ID 


Pour ajouter une relation, cela peut se faire pendant la création de l'entité si l'autre entité de la relation est deja créée.
Sinon il faudra revenir le faire plus tard.
```
 New property name (press <return> to stop adding fields):
 > exampleRelation

 Field type (enter ? to see all types) [string]:
 > relation

What class should this entity be related to?:
 > AutreEntity
 
What type of relationship is this?
 ------------ ----------------------------------------------------------------- 
  Type         Description                                                      
 ------------ ----------------------------------------------------------------- 
  ManyToOne    Each Test relates to (has) one Event.                            
               Each Event can relate to (can have) many Test objects            
                                                                                
  OneToMany    Each Test can relate to (can have) many Event objects.           
               Each Event relates to (has) one Test                             
                                                                                
  ManyToMany   Each Test can relate to (can have) many Event objects.           
               Each Event can also relate to (can also have) many Test objects  
                                                                                
  OneToOne     Each Test relates to (has) exactly one Event.                    
               Each Event also relates to (has) exactly one Test.               
 ------------ ----------------------------------------------------------------- 

Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > 
 ```

## Database

Pour creér la base de donnée :
- 1ère étape, créer la base sans les tables.
```
php bin/console doctrine:database:create
```
- 2ème étape, créer le fichier de migration. 
```
php bin/console make:migration
```
- 3ème étape, après avoir vérifié le fichier de migration il faut l'executer.
```
php bin/console doctrine:migrations:migrate
```

Pour les **modifications, mise a jour de la base donnée** : 
```
php bin/console doctrine:migration:diff
```
Il n'y a plus qu'a suivre le prompt.

 ---

# NORME
 
## Code :
```php
    class City  // 1ere lettre en majuscule = PASCAL CASE
{
    private $id;
    private $name;
    private $departement;
    private $exampleAttributEnCamelCase; /* 1ere lettre en minuscule puis majuscule pour séparer les mots = CAMEL CASE*/
}
```
## Base de donnée :
```sql
    example_attribut -- Tout en minuscule et _ pour séparer les mots = SNAKE CASE
```
