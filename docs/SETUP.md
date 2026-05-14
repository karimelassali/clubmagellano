# Setup rapido

## Backend
1. Crea un progetto Laravel 11 o 12.
2. Copia il contenuto di `backend/` sopra il progetto fresh.
3. Installa dipendenze:
   - `composer install`
   - `php artisan key:generate`
   - configura `.env`
4. Esegui:
   - `php artisan migrate --seed`
   - `php artisan serve`
5. Avvia un worker queue:
   - `php artisan queue:work`

Utente seed:
- email: `senior@example.com`
- password: `password`

## Frontend
1. Crea un progetto Angular 17+ standalone oppure usa quello incluso come base.
2. Da `frontend/` esegui:
   - `npm install`
   - `npm start`
