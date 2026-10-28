# Modélisation et conception : avecLesFemmes

**Étudiante :** Aminata CISSE
**Cours :** Symfony 7.4, Professeur Baila Wane
**Base de données :** PostgreSQL (base `avecLesFemmes`)

---

## 1. Contexte métier

L'application gère les **calebasses participatives** (épargne, tontine et prêt) de groupements de femmes. Chaque membre cotise régulièrement. L'argent collecté est soit épargné collectivement, soit remis tour à tour à un membre (tontine), soit prêté aux membres qui en font la demande.

## 2. Acteurs et cas d'utilisation

Les diagrammes se trouvent dans ce dossier :
`use_cases_admin.png`, `use_cases_membre.png`, `use_cases_secretaire.png`.

| Acteur | Cas d'utilisation |
|---|---|
| **Admin** | Gérer les groupements ; gérer les membres (inclut : attribuer les rôles) ; créer une campagne (tontine, épargne, configuration d'une campagne de prêt) ; valider une demande de prêt (extension : refuser une demande) |
| **Membre** | S'authentifier ; adhérer à une campagne (inclut : choisir le mode de cotisation) ; faire une demande de prêt ; consulter son historique |
| **Secrétaire** | Enregistrer une séance de cotisation ; enregistrer le remboursement d'un prêt (extension : calculer et appliquer l'amende de retard) |

## 3. Diagramme de classes

Le diagramme est dans `diagramme_classes.png`.

### Entités et attributs

| Entité | Type | Attributs propres | Relations |
|---|---|---|---|
| `User` | Superclasse (héritage JOINED) | nomComplet (150), telephone (20), adresse (255), login (50), password (255) | Aucune |
| `Membre` | Hérite de `User` | matricule (50) | ManyToOne vers `Role` ; ManyToOne vers `Groupement` ; OneToMany vers `ModeCotisation` ; OneToMany vers `DemandePret` |
| `Admin` | Hérite de `User` | niveauAcces (50) | ManyToOne vers `Groupement` |
| `Role` | Entité simple | nomRole (50) | OneToMany vers `Membre` |
| `Groupement` | Entité simple | nom (100) | OneToMany vers `Membre`, `Admin` et `Campagne` |
| `Campagne` | Superclasse (héritage JOINED) | nom (100), dateDebut, dateFin (nullable), montant, createdAt (immuable), updatedAt (nullable) | ManyToOne vers `Groupement` ; OneToMany vers `ModeCotisation` |
| `Epargne` | Hérite de `Campagne` | tauxInteret | Aucune |
| `Tontine` | Hérite de `Campagne` | ordreTirage | Aucune |
| `Pret` | Hérite de `Campagne` | tauxInteretPret, dureeMaxMois, tauxPenaliteRetard | OneToMany vers `DemandePret` |
| `ModeCotisation` | Table d'association | isLibre (booléen) | ManyToOne vers `Membre` ; ManyToOne vers `Campagne` |
| `DemandePret` | Entité simple | montantDemande, motif (255), statut (50), dateDemande, dateEcheance, montantAmende (nullable), estRembourse | ManyToOne vers `Membre` ; ManyToOne vers `Pret` |

### Choix de modélisation

- **Héritage JOINED** pour `User` et `Campagne` : chaque classe fille a sa propre table, reliée à la table de la classe mère. Une colonne discriminante `type` indique la classe réelle de chaque ligne.
- **Table d'association `ModeCotisation`** : la relation plusieurs-à-plusieurs entre `Membre` et `Campagne` porte un attribut propre (`isLibre`). Elle est donc cassée en deux relations ManyToOne. Si `isLibre` est faux, le membre cotise le montant fixe de la campagne ; s'il est vrai, il saisit un montant libre à chaque séance.
- **`Role` et `Membre`** : plusieurs membres peuvent avoir le même rôle, la relation est donc un ManyToOne côté `Membre`.
- **`montantAmende`** : orthographe corrigée par rapport au schéma initial (`montantAmande`). Le champ est nullable, car l'amende n'existe qu'en cas de retard.
- **Séance de cotisation** : elle apparaît dans les cas d'utilisation du secrétaire mais pas encore dans le diagramme de classes. Elle sera modélisée dans une étape ultérieure.

## 4. Commandes personnalisées Composer

Déclarées dans `composer.json` (section `scripts`), avec le préfixe `aminatacisse`. Les commandes natives de Symfony ne sont plus utilisées directement.

| Commande | Rôle |
|---|---|
| `composer aminatacisse:db:create` | Supprime la base si elle existe, puis la recrée |
| `composer aminatacisse:cache:clear` | Vide le cache |
| `composer aminatacisse:make:entity` | Crée ou complète une entité |
| `composer aminatacisse:migration` | Génère la migration puis l'exécute |

### Problème rencontré sous Windows

Sous Windows, Composer ne transmet pas le clavier (STDIN) aux commandes lancées depuis `scripts`. La commande `make:entity`, qui pose des questions, s'arrêtait avec l'erreur `MissingInputException : Aborted`.

**Solution :** la classe `App\Composer\CommandesAminata` (fichier `src/Composer/CommandesAminata.php`) lance `make:entity` avec `proc_open` en lui donnant directement le clavier et l'écran du terminal (STDIN, STDOUT, STDERR). Le script `aminatacisse:make:entity` appelle `App\Composer\CommandesAminata::makeEntity`. Après l'ajout de la classe, il faut lancer `composer dump-autoload`.

## 5. Stratégie de versionnement

Une action est égale à un commit, avec un message explicite. Ordre suivi :

1. Wrapper `CommandesAminata.php`
2. `User` (héritage JOINED)
3. `Role`
4. `Groupement`
5. `Membre` (extends `User`, relations `Role` et `Groupement`)
6. `Admin` (extends `User`)
7. Ajout du `DiscriminatorMap` dans `User`
8. `Campagne` (héritage JOINED)
9. `Epargne`, `Tontine`, `Pret`
10. Ajout du `DiscriminatorMap` dans `Campagne`
11. `ModeCotisation`
12. `DemandePret`
13. Migration PostgreSQL
14. Installation du bundle Doctrine Fixtures
15. Fixtures (jeux de données de test)

Le `DiscriminatorMap` de chaque classe mère est ajouté seulement après la création de ses classes filles, car Doctrine refuse une carte qui cite une classe inexistante.

Les branches suivent GitFlow : `main`, `develop` et une branche `feature/...` par fonctionnalité, fusionnée dans `develop` puis dans `main`.

## 6. Contenu du dépôt

- `docs/` : diagrammes UML (classes et cas d'utilisation) et ce fichier
- `src/Entity/` et `src/Repository/` : les 12 entités et leurs repositories
- `src/Composer/CommandesAminata.php` : wrapper pour la commande interactive
- `src/DataFixtures/AppFixtures.php` : données de test (4 rôles, 2 groupements, 2 admins, 7 membres, 5 campagnes, modes de cotisation et demandes de prêt)
- `migrations/` : schéma de la base de données