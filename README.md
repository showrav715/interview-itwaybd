# Interview ItWayBD - Project Setup Instructions

Follow these steps to set up the **Interview ItWayBD** project locally:

## 1. Clone the Repository

```
git clone https://github.com/showrav715/interview-itwaybd.git
cd interview-itwaybd
```

## 2. Install Dependencies

```
composer install
```

> ⚠️ If dependencies are already installed, you can update them using:

```
composer update
```

## 3. Configure Environment

1. Copy the example environment file:

```
cp .env.example .env
```

2. Update the `.env` file with your **database credentials** and other environment-specific settings.

## 4. Run Migrations

```
php artisan migrate
```

## 5. Seed the Database

```
php artisan db:seed
```

> ⚠️ Note: This may take **2–3 minutes** because the seeder includes a large amount of data for testing filters and query performance optimization.

Or You can import manullay database.sql file on your phpmyadmin i alrady shared database.sql file 


## 6. Run the Application

```
php artisan serve
```

> The application will be available at `http://localhost:8000`

---


