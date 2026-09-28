# Q1

- Code de statut : 200
- Content-type : application/json

# Q2

- Code : 404
- C'est le client qui doit corriger la valeur de l'id envoye en segment de l'URI

# Q3

- Code : 201
- Identifiant attribue : 101
- L'en-tet Location est present

# Q4

- GET /posts/101 renvoie le livre ayant l'identifiant 101 sous forme JSON
- Cela revele sur JSONPlaceholder que les livres qu'il peut envoyer se limite a moins de 101

# Q5

- Code : 200
- D'apres le cours, l'autre qu'on aurait pu attendre est 204

# Q6

- Apres un post reussi, l'article pourrait apparaitre dans la liste rechargee suivant la valeur du parametre '_limit' parce que ce parametre limite le nombres de livres recuperes depuis l'API

# Q7

- Le naviguateur autorise l'autorise parce que l'en-tete Cors associe a la requete

# Q8

- respondCreated renvoie 201
- Non, il n'ajoute pas l'en-tete Location

# Q9

- Pour DELETE j'ai choisi 200
- j'ai choisi 200 car il est plus approprie pour indiquer que l'operation a ete effectuee avec succes et qu'on a nul besoin de renvoyer des donnees en dehors du retour a la page precedent.

# Q10

- Un PUT qui omet le champ auteur ne doit pas reussir
- Un PATCH qui omet ce meme champ devrait reussir

```php
    $livre = $this->model->find($id);
    $data = $this->request->getJSON(true);
    # ON a besoin uniquement de melanger le livre et les donnees de la requete que lorsque la methode HTTP est PUT
    if($this->request->getMethod() === 'put'){
        $data = array_merge($livre, $data);
    } 
```
