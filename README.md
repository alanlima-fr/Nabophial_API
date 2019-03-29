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

 ---

# NORME
 
## Code :
```php
    class City  // 1ere lettre en majuscule
{
    private $id;
    private $name;
    private $departement;
    private $exampleAttributEnCamelCase; /* 1ere lettre en minuscule puis majuscule pour séparer les mots */
}
```
## Base de donnée :
```sql
    example_attribut -- Tout en minuscule et _ pour séparer les mots
```