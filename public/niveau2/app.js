// Niveau 2 : consommer une API REST existante avec fetch.
const API = 'https://jsonplaceholder.typicode.com/posts';

const liste = document.getElementById('liste');
const message = document.getElementById('message');
const form = document.getElementById('form-article');

function afficher(texte, classe) {
  message.textContent = texte;
  message.className = classe;
}

// GET : les 5 premiers articles
async function charger() {
  const url = `${API}?_limit=5`;
  const response = await fetch(url, {
    headers: {
      'Accept': 'application/json',
    }
  });
  if (!response.ok) {
    afficher(`Erreur ${response.status}`, 'erreur');
    throw new Error(`Erreur ${response.status}`);
  }
  const articles = await response.json();
  liste.innerHTML = '';
  for(const article of articles) {
    const li = document.createElement('li');
    li.textContent = `${article.id} - ${article.title}`;
    const btn = document.createElement('button');
    btn.textContent = 'Supprimer';
    btn.addEventListener('click', () => {
       supprimer(article.id);
    });
    li.appendChild(btn);
    liste.appendChild(li);
  }
  // TODO 1 : appeler `${API}?_limit=5` avec l'en-tête Accept: application/json.
  // TODO 2 : si reponse.ok est faux, afficher le code d'erreur et s'arrêter.
  // TODO 3 : vider #liste, puis créer un <li> par article (id et title)
  //          avec un bouton « Supprimer » qui appelle supprimer(article.id).
}

// POST : créer un article
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const { title, body } = Object.fromEntries(new FormData(form));
  const response = await fetch(API, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ title, body, userId: 1 })
  });

  if (response.ok && response.status === 201) {
    const article = await response.json();
    afficher(`Article créé avec l'id ${article.id}`, 'ok');
    charger();
  } else {
    afficher(`Erreur ${response.status}`, 'erreur');
    throw new Error(`Erreur ${response.status}`);
  }

  // TODO 4 : envoyer { title, body, userId: 1 } en JSON (POST, en-tête Content-Type).
  // TODO 5 : si le statut est 201, afficher l'identifiant attribué puis recharger la liste.
  //          Le nouvel article apparaît-il ? Pourquoi ?
});

// DELETE : supprimer un article
async function supprimer(id) {
  const url = `${API}/${id}`;
  const response = await fetch(url, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    }
  });
  if (!response.ok) {
    afficher(`Erreur ${response.status}`, 'erreur');
    throw new Error(`Erreur ${response.status}`);
  }
  afficher(`Article ${id} supprimé`, 'ok');
  charger();
  // TODO 6 : envoyer DELETE sur `${API}/${id}` et afficher le code reçu.
}

charger();
