# Salutem

## TODO

- [ ] Afficher sur la page d'un centre de santé si celui-ci est ouvert ou fermé.
___
- [ ] Pouvoir gérer les utilisateurs lorsque l'on est connecté en tant qu'administrateur.
___
- [ ] Rattacher l'entité `User` à l'entité `HealthcareCenter` et gérer un nouveau rôle `ROLE_MANAGER` et leur permettre de se connecter à l'administration avec un accès restreint aux données des centres de santé qu'ils gèrent.
- [ ] Pouvoir gérer les horaires d'ouverture d'un centre de santé lorsque l'on est connecté en tant que gérant.
- [ ] Pouvoir gérer les rendez-vous d'un centre de santé lorsque l'on est connecté en tant que gérant.
- [ ] Pouvoir gérer les docteurs d'un centre de santé lorsque l'on est connecté en tant que gérant.
- [ ] Pouvoir afficher les rendez-vous d'un docteur lorsque l'on est connecté en tant que gérant.
- [ ] Améliorer l'administration des horaires d'ouverture en utilisant les [Collection de formulaire](https://symfony.com/doc/current/form/form_collections.html).
___
- [ ] Rattacher l'entité `User` à l'entité `Appointment` et afficher un formulaire simplifié pour la prise de rendez-vous lorsque l'utilisateur est connecté.
- [ ] Pouvoir afficher les rendez-vous (passés et à venir) d'un utilisateur lorsque l'on est connecté en tant qu'utilisateur.

## Création du projet

### Installation de Symfony

Lien vers la documentation de l'installation de Symfony
[ici](https://symfony.com/doc/current/setup.html).

```bash
composer create-project symfony/skeleton salutem
```

Ou avec le CLI de Symfony

```bash
symfony new salutem # ou symfony new salutem --webapp
```

### Installation des outils de debug et de Maker

Lien vers la documentation de Debug
[ici](https://symfony.com/doc/current/components/var_dumper.html).

```bash
composer req --dev debug
composer req --dev maker
```

### Création du premier contrôleur

Lien vers la documentation des contrôleurs
[ici](https://symfony.com/doc/current/controller.html).

```bash
php bin/console make:controller MainController
```

### Installation du moteur de template Twig

Lien vers la documentation de Twig
[ici](https://symfony.com/doc/current/templates.html).

```bash
composer req twig
```

### Installation de AssetMapper

Lien vers la documentation de AssetMapper
[ici](https://symfony.com/doc/current/frontend/asset_mapper.html).

Il est possible pour cette étape d'utiliser
AssetMapper (simple, mais limité) ou
Webpack Encore (plus complexe, mais plus puissant).

```bash
composer req symfony/asset-mapper symfony/asset
```

### Installation de Doctrine

Lien vers la documentation des entités
[ici](https://symfony.com/doc/current/doctrine.html).

```bash
composer req orm
```

Créer le fichier `.env.local` et y ajouter la configuration de la base de données.

```bash
DATABASE_URL="mysql://salutem:salutem@127.0.0.1:3306/salutem?serverVersion=5.7&charset=utf8mb4"
```

Créer la base de données (sauf si elle existe déjà).

```bash
php bin/console doctrine:database:create
```

### Création des entités

Lien vers la documentation des entités
[ici](https://symfony.com/doc/current/doctrine.html).

```bash
php bin/console make:entity
```

Attention ! Pour les utilisateurs :

```bash
php bin/console make:user
```

Création des fichiers de migration.

```bash
php bin/console make:migration
```

Mise à jour de la base de données.

```bash
php bin/console doctrine:migrations:migrate
```

### Installation de Doctrine Extensions

Lien vers la documentation de Doctrine Extensions
[ici](https://symfony.com/bundles/StofDoctrineExtensionsBundle/current/index.html).

```bash
composer req stof/doctrine-extensions-bundle
```

Puis activer les extensions dans le fichier `config/packages/stof_doctrine_extensions.yaml`.

```yaml
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            sluggable: true
```

### Création des fixtures

Lien vers la documentation des fixtures
[ici](https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html).

```bash
composer req orm-fixtures
```

Création des fixtures.

```bash
php bin/console make:fixtures
```

### Gestion des formulaires

Lien vers la documentation des formulaires
[ici](https://symfony.com/doc/current/forms.html).

Lien vers la documentation des validateurs
[ici](https://symfony.com/doc/current/validation.html).

```bash
composer req form
composer req validator
```

Créer un formulaire.

```bash
php bin/console make:form
```

### Gestion des utilisateurs

Lien vers la documentation de la sécurité
[ici](https://symfony.com/doc/current/security.html).

```bash
composer req security
```

Créer un utilisateur.

```bash
php bin/console make:user
```

Créer les fixtures pour les utilisateurs.

```bash
php bin/console make:fixtures UserFixtures
```

Créer le formulaire de connexion.

```bash
php bin/console make:security:form-login
```

Créer le formulaire d'inscription.

```bash
composer req symfonycasts/verify-email-bundle
composer req mailer
php bin/console make:registration-form
```
