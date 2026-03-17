# Configuration SMTP – ESEBAT

L’envoi d’emails (mot de passe oublié, etc.) utilise la configuration mail de Laravel. Par défaut le projet est en `MAIL_MAILER=log` : les emails sont écrits dans les logs, pas envoyés.

## 1. Variables dans `.env`

À définir dans votre fichier **`.env`** (ne pas commiter les mots de passe).

| Variable | Description | Exemple |
|----------|-------------|---------|
| `MAIL_MAILER` | Driver : `smtp`, `log`, `ses`, etc. | `smtp` |
| `MAIL_HOST` | Serveur SMTP | `smtp.gmail.com` |
| `MAIL_PORT` | Port (587 TLS, 465 SSL, 25 non sécurisé) | `587` |
| `MAIL_USERNAME` | Compte SMTP | `votre@email.com` |
| `MAIL_PASSWORD` | Mot de passe ou mot de passe d’application | secret |
| `MAIL_ENCRYPTION` | `tls`, `ssl` ou `null` | `tls` |
| `MAIL_FROM_ADDRESS` | Adresse expéditeur | `noreply@votredomaine.com` |
| `MAIL_FROM_NAME` | Nom expéditeur | `ESEBAT` |

Laravel lit aussi `MAIL_URL` (une seule URL) ou `MAIL_SCHEME` selon la version.

## 2. Exemples de configuration

### En développement : tout en log (défaut)

Aucun serveur SMTP. Les emails sont enregistrés dans `storage/logs/laravel.log`.

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="esebat@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Mailtrap (tests sans envoyer de vrais emails)

1. Créer un compte sur [mailtrap.io](https://mailtrap.io).
2. Créer une inbox, récupérer les identifiants SMTP.
3. Dans `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="esebat@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Gmail

- Utiliser un **mot de passe d’application** (compte Google avec 2FA), pas le mot de passe du compte.
- Ou un compte de test dédié.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre@gmail.com
MAIL_PASSWORD=mot_de_passe_application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Outlook / Microsoft 365

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=votre@domaine.com
MAIL_PASSWORD=mot_de_passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre@domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Serveur SMTP interne ou hébergeur

Adapter selon les infos fournies par l’hébergeur (ex. OVH, o2switch, etc.) :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-hebergeur.com
MAIL_PORT=587
MAIL_USERNAME=noreply@votredomaine.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votredomaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 3. Après modification du `.env`

- Redémarrer le serveur web / PHP si besoin (pour recharger les variables d’environnement).
- Vider le cache de config si vous en utilisez un en prod :  
  `php artisan config:clear`

## 4. Tester l’envoi

- **Mot de passe oublié** : aller sur la page « Mot de passe oublié », saisir un email d’un utilisateur existant, valider. Avec `MAIL_MAILER=log`, le lien apparaît dans `storage/logs/laravel.log`.
- **Test manuel** :
  ```bash
  php artisan tinker
  >>> Mail::raw('Test SMTP', fn ($m) => $m->to('test@example.com')->subject('Test'));
  ```

## 5. Sécurité

- Ne jamais commiter `.env` (déjà dans `.gitignore`).
- En production : utiliser des identifiants dédiés (compte « noreply ») et un mot de passe fort ou une clé d’API selon le fournisseur.
