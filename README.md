# Qontact

```bash
unzip qontact.zip
cd qontact
composer install

cp .env.example .env
php artisan key:generate

# Set up your database in .env
php artisan migrate:f --seed
php artisan serve

# Run workers on a separate terminal
php artisan queue:work --tries=3 --timeout=60
```

## Usage

Open your browser and navigate to `http://localhost:8000` to access the Qontact application.

Login with the credentials:

- Email: `admin@qontact.app`
- Password: `password`
