# Modélisation et conception : avecLesFemmes (version 2, Livrable 2)

**Étudiante :** Aminata CISSE
**Cours :** Symfony 7.4, Professeur Baila Wane
**Base de données :** PostgreSQL (base `avecLesFemmes`)
**Périmètre du Livrable 2 :** rôle de super-administrateur (`Sup_Admin`)

---

## 1. Contexte métier

L'application « Avec les Femmes » gère les tontines et l'épargne de groupements de femmes. Chaque membre cotise régulièrement à une campagne (épargne, tontine ou prêt) rattachée à un exercice d'un groupement. À chaque séance, une cotisation est enregistrée par membre, avec une contribution à la caisse sociale. Les absents sans paiement reçoivent une amende journalière.

Règle de la caisse sociale :

| Situation du membre | Conséquence (exemple) |
|---|---|
| Présent | contribution « présent » (100 FCFA) |
| Absent représenté | contribution « absent » (300 FCFA) |
| Absent sans paiement | amende journalière (350 FCFA par jour), accumulée jusqu'au prochain paiement |

## 2. Diagrammes

Dans le dossier `docs/` :

- `classe_diagramme.png` : diagramme de classes de la version 2 (référence du livrable)
- `use_cases_sup_admin.png` : cas d'utilisation du super-administrateur
- `diagramme_classes.png`, `use_cases_admin.png`, `use_cases_membre.png`, `use_cases_secretaire.png` : version 1 (conservés pour l'historique)

## 3. Modèle de données (version 2)

### Entités

| Entité | Attributs | Relations |
|---|---|---|
| `Groupement` | nom, telephone, adresse (nullable) | 1 → N `Exercice` (orphanRemoval) ; 1 → N `Membre` |
| `Exercice` | dateDebut (immuable), dateFin, dateLimitePret, dateRetribution, statut (`StatutExercice`) | N → 1 `Groupement` (jamais vide) ; 1 → N `Campagne` |
| `Campagne` (mère, JOINED) | nom, dateDebut (immuable), dateFin (nullable), montant, periodicite (`Periodicite`), jourRencontre, contribCaissePresent, contribCaisseAbsent, amendeJournaliere | N → 1 `Exercice` ; 1 → N `Seance` ; 1 → N `ModeCotisation` |
| `Epargne`, `Tontine`, `Pret` | aucun attribut propre | héritent de `Campagne` |
| `Seance` | dateSeance | N → 1 `Campagne` ; 1 → N `Cotisation` ; 1 → N `Amende` |
| `Cotisation` | montant, contribCaisse, presence (`StatutPresence`) | N → 1 `Seance` ; N → 1 `Membre` |
| `Amende` | montant, nbJours, estPayee | N → 1 `Seance` ; N → 1 `Membre` |
| `User` (mère, JOINED) | nomComplet, telephone, adresse, login, password | `Membre` en hérite |
| `Membre` | aucun attribut propre | N → 1 `Groupement` ; N → 1 `Role` ; 1 → N `ModeCotisation`, `Cotisation`, `Amende` |
| `Role` | nomRole | 1 → N `Membre` |
| `ModeCotisation` | isLibre (faux = montant fixe, vrai = montant libre) | N → 1 `Membre` ; N → 1 `Campagne` |

Les entités `Admin` et `DemandePret` de la version 1 ont été retirées, car elles sont absentes du diagramme v2.

### Énumérations (`src/Enum/`)

Enums de type backed, chacun avec une méthode `label()` écrite avec `match`. Ce ne sont jamais des relations Doctrine.

| Enum | Valeurs |
|---|---|
| `StatutExercice` | `en_cours`, `cloture` |
| `Periodicite` | `hebdomadaire`, `bimensuelle`, `mensuelle`, `quinzaine` |
| `StatutPresence` | `present`, `absent_represente`, `absent_sans_paiement` |

### Trait `App\Trait\DateTimeTrait`

Fournit `createdAt` (immuable) et `updatedAt`, remplis par les callbacks `PrePersist` et `PreUpdate`. Chaque entité qui l'utilise porte `#[ORM\HasLifecycleCallbacks]` (placé seulement dans le trait, l'attribut serait ignoré).

### Choix de modélisation

- **Héritage JOINED** pour `User` et `Campagne` : une table par classe, colonne discriminante `type`. Le `DiscriminatorMap` n'est ajouté qu'une fois les classes filles créées.
- **`ModeCotisation`** : association entre `Membre` et `Campagne` portant l'attribut `isLibre`. Les membres d'une campagne sont ceux qui y ont un mode de cotisation.
- **Fonds de prêt** : une campagne `Pret` n'a pas de séance de cotisation.

## 4. Architecture applicative

`Controller → Service → Repository → DTO`

| Couche | Dossier | Rôle |
|---|---|---|
| Contrôleurs | `src/Controller/` | reçoivent la requête, appellent le service, renvoient la vue (404 si l'identifiant n'existe pas) |
| Services | `src/Service/` | construisent les DTO et calculent les totaux ; dépendances en `private readonly` |
| Repositories | `src/Repository/` | requêtes DQL (QueryBuilder) avec jointures chargées |
| DTO | `src/DTO/` | données prêtes à afficher : dates converties en texte, exercice en cours résolu |
| Utilitaire | `src/Util/DateFr.php` | date en texte français (ex. « 4 janvier 2026 ») |
| Vues | `templates/` | Twig sans logique métier |

### Pages du Livrable 2

| Route | Contrôleur | Contenu |
|---|---|---|
| `/groupement` (et `/`) | `GroupementController` | groupements avec exercice en cours |
| `/groupement/{id}/campagnes` | `CampagneController` | campagnes d'un groupement |
| `/campagne/{id}/membres` | `CampagneController` | membres d'une campagne avec mode de cotisation |
| `/campagne/{id}/seances` | `CampagneController` | séances avec cotisations, amendes et trois totaux |

### Totaux par séance (`SeanceService`)

- **Total collecté** : somme des `montant` des cotisations de la séance
- **Total des amendes** : somme des `montant` des amendes de la séance
- **Caisse sociale** : somme des `contribCaisse` des cotisations de la séance

Le service charge cotisations et amendes de toute la campagne en deux requêtes, les regroupe par séance, puis calcule les sommes.

## 5. Commandes personnalisées Composer

Déclarées dans `composer.json` (préfixe `aminatacisse`). Les commandes natives de création ne sont pas utilisées directement.

| Commande | Rôle |
|---|---|
| `composer aminatacisse:db:create` | supprime et recrée la base |
| `composer aminatacisse:cache:clear` | vide le cache |
| `composer aminatacisse:make:entity` | crée ou complète une entité |
| `composer aminatacisse:migration` | génère puis exécute la migration |

**Problème Windows :** Composer ne transmet pas le clavier aux commandes lancées depuis `scripts`, et `make:entity` s'arrêtait (« Aborted »). La classe `App\Composer\CommandesAminata` lance la commande avec `proc_open` en branchant STDIN, STDOUT et STDERR.

## 6. Fixtures

Ordre d'exécution (dépendances déclarées avec `DependentFixtureInterface`) : `GroupementFixtures` et `RoleFixtures`, puis `ExerciceFixtures` et `MembreFixtures`, puis `CampagneFixtures`, puis `ModeCotisationFixtures` et `SeanceFixtures`, puis `CotisationFixtures`, puis `AmendeFixtures`.

Données produites : 3 groupements (Hospitalité, Espoir, Les Femmes Unies), 4 rôles, 6 exercices (un clôturé et un en cours par groupement), 12 membres, 9 campagnes (épargne, tontine, prêt), 24 modes de cotisation, 24 séances, 96 cotisations et les amendes des absents sans paiement.

Chargement : `php bin/console doctrine:fixtures:load`.

## 7. Stratégie de versionnement

Une action = un commit, avec un message explicite. Une branche par chantier, fusionnée dans `develop` (`--no-ff`) quand elle est stable.

| Branche | Contenu |
|---|---|
| `main` / `develop` | version 1 puis intégration continue |
| `livrable/2-modelisation` | diagrammes v2 |
| `livrable/2-entity` | enums, trait, entités v2, relations, migration |
| `livrable/2-fixtures` | 9 fixtures |
| `livrable/2-listes` | DTO, repositories, services, contrôleurs, vues |
| `livrable/2-documentation` | cette documentation |
| `livrable/2` | branche de rendu demandée par le professeur |

Le fichier `.env.local` n'est jamais commité (il figure dans `.gitignore`).

## 8. Contenu du dépôt

- `docs/` : diagrammes et ce fichier
- `src/Entity/`, `src/Enum/`, `src/Trait/` : modèle de données
- `src/Repository/`, `src/Service/`, `src/DTO/`, `src/Controller/`, `src/Util/` : logique applicative
- `src/DataFixtures/` : données de test
- `src/Composer/CommandesAminata.php` : correctif Windows des commandes
- `migrations/` : historique du schéma
- `templates/` et `assets/styles/app.css` : vues Twig et style