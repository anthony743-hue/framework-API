#!/usr/bin/env bash
# =============================================================
# tests_api.sh — Tests curl pour les API Livres & Members
# Usage : bash tests_api.sh
# =============================================================

BASE_URL="http://localhost:8080"
LIVRES="$BASE_URL/api/livres"
MEMBERS="$BASE_URL/api/members"

sep() {
    echo ""
    echo "============================================================"
    echo "  $1"
    echo "============================================================"
}

# =============================================================
#  API LIVRES
# =============================================================

    curl -i  "http://localhost:8080/api/livres/2/1"

curl -i -X PATCH "http://localhost:8080/api/livres/2" \
  -H "Content-Type: application/json" \
  -d '{"annee":1950}'

sep "1.1  GET /api/livres — lister tous les livres"
curl -i -X GET "$LIVRES"
echo ""

sep "1.2  GET /api/livres?page=2&par_page=5 — pagination"
curl -i -X GET "$LIVRES?page=2&par_page=5"
echo ""

sep "1.3  GET /api/livres/1 — afficher un livre"
curl -i -X GET "$LIVRES/1"
echo ""

sep "1.4  GET /api/livres/9999 — livre inexistant (404 attendu)"
curl -i -X GET "$LIVRES/9999"
echo ""

sep "1.5  POST /api/livres — créer un livre valide (201 attendu)"
curl -i -X POST "$LIVRES" \
  -H "Content-Type: application/json" \
  -d '{"titre":"Le Petit Prince","auteur":"Antoine de Saint-Exupéry","annee":1943}'
echo ""

sep "1.6  POST /api/livres — livre invalide (erreurs attendues)"
curl -i -X POST "$LIVRES" \
  -H "Content-Type: application/json" \
  -d '{"titre":"","auteur":"","annee":"abc"}'
echo ""

sep "1.7  PUT /api/livres/1 — remplacement complet"
curl -i -X PUT "$LIVRES/1" \
  -H "Content-Type: application/json" \
  -d '{"titre":"1984","auteur":"George Orwell","annee":1949}'
echo ""

sep "1.8  PATCH /api/livres/1 — modification partielle"
curl -i -X PATCH "$LIVRES/1" \
  -H "Content-Type: application/json" \
  -d '{"annee":1950}'
echo ""

sep "1.9  PUT /api/livres/9999 — livre inexistant (404 attendu)"
curl -i -X PUT "$LIVRES/9999" \
  -H "Content-Type: application/json" \
  -d '{"titre":"X","auteur":"Y","annee":2000}'
echo ""

sep "1.10 DELETE /api/livres/1 — supprimer un livre"
curl -i -X DELETE "$LIVRES/1"
echo ""

sep "1.11 DELETE /api/livres/9999 — livre inexistant (404 attendu)"
curl -i -X DELETE "$LIVRES/9999"
echo ""

# =============================================================
#  API MEMBERS
# =============================================================

sep "2.1  GET /api/members — lister tous les membres"
curl -i -X GET "$MEMBERS"
echo ""

sep "2.2  GET /api/members?page=1&par_page=5 — pagination"
curl -i -X GET "$MEMBERS?page=1&par_page=5"
echo ""

sep "2.3  GET /api/members/1 — afficher un membre"
curl -i -X GET "$MEMBERS/1"
echo ""

sep "2.4  GET /api/members/9999 — membre inexistant (404 attendu)"
curl -i -X GET "$MEMBERS/9999"
echo ""

sep "2.5  POST /api/members — créer un membre"
curl -i -X POST "$MEMBERS" \
  -H "Content-Type: application/json" \
  -d '{"nom":"Alice Dupont","email":"alice@example.com","password":"secret123"}'
echo ""

sep "2.6  PUT /api/members/1 — remplacement complet"
curl -i -X PUT "$MEMBERS/1" \
  -H "Content-Type: application/json" \
  -d '{"nom":"Alice Martin","email":"alice.martin@example.com","password":"newpass"}'
echo ""

sep "2.7  PATCH /api/members/1 — modification partielle"
curl -i -X PATCH "$MEMBERS/1" \
  -H "Content-Type: application/json" \
  -d '{"email":"nouvel.email@example.com"}'
echo ""

sep "2.8  PUT /api/members/9999 — membre inexistant (404 attendu)"
curl -i -X PUT "$MEMBERS/9999" \
  -H "Content-Type: application/json" \
  -d '{"nom":"X","email":"x@x.com","password":"x"}'
echo ""

sep "2.9  DELETE /api/members/1 — supprimer un membre"
curl -i -X DELETE "$MEMBERS/1"
echo ""

sep "2.10 DELETE /api/members/9999 — membre inexistant (404 attendu)"
curl -i -X DELETE "$MEMBERS/9999"
echo ""

# =============================================================
#  BONUS — Vérification du header Location après POST
# =============================================================

sep "3.1  POST /api/livres — vérifier la présence du header Location"
curl -i -X POST "$LIVRES" \
  -H "Content-Type: application/json" \
  -d '{"titre":"Dune","auteur":"Frank Herbert","annee":1965}'
echo ""

sep "3.2  POST + GET enchaînés via jq (si installé)"
if command -v jq >/dev/null 2>&1; then
    ID=$(curl -s -X POST "$LIVRES" \
        -H "Content-Type: application/json" \
        -d '{"titre":"Sapiens","auteur":"Yuval Noah Harari","annee":2011}' \
        | jq -r '.messages.id')
    echo "Livre créé avec l'id : $ID"
    curl -i -X GET "$LIVRES/$ID"
else
    echo "jq n'est pas installé — étape ignorée."
fi
echo ""

sep "FIN DES TESTS"