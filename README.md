# usbeaufort
Web App USBeaufort pour le tournoi de mai

## Dépendances
- composer
- npm

## Configuration
- créer le fichier back/usbeaufort-api/.env-local avec :
- DATABASE_URL="mysql://root:root@127.0.0.1:3306/usbeaufort?serverVersion=5.7"
- APP_ENV=dev

## Commandes

- php back/usbeaufort-api/bin/console doc:migrations:migrate

## Scripts
- npm run front (pour afficher le front)
- npm run back (pour gérer le back)