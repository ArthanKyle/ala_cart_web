# Ala_Cart_Web

This is the Laravel web application that serves as the backend for the Ala_Cart_Mobile application.

## Prerequisites

Ensure you have the following installed on your system:

-   PHP
-   Composer
-   Laravel

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/ArthanKyle/ala_cart_web.git
    cd ala_cart_web
    ```

2. **Install Dependencies**

    ```bash
    composer update
    ```

3. **Install Dependencies**

    Verify the PHP configuration file:

    ```bash
    php --ini
    ```

4. **Update php.ini**

    Make necessary changes to your php.ini file, such as enabling required extensions like:

    - `pdo`
    - `mbstring`
    - `intl`
    - `mysqli`

5. **Set up environment file**

    Copy the example `.env` file:

    ```bash
    cp .env.example .env
    ```

6. **Generate application key**

    Verify the PHP configuration file:

    ```bash
    php artisan key:generate
    ```

## Database Setup

1. **Install Lunar:**

    ```bash
    php artisan lunar:install
    ```

2. **Run migrations:**

    ```bash
    php artisan migrate
    ```

3. **Seed the database:**

    ```bash
    php artisan db:seed
    ```

4. **Link storage:**

    ```bash
    php artisan storage:link
    ```

### Clear Caches

**Ensure a clean state by clearing various caches:**

1. **Clear caches:**

    ```bash
    php artisan livewire:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan config:clear
    php artisan route:clear
    ```

### Livewire Setup

1. **Install Livewire:**

    ```bash
    composer require livewire/livewire
    ```

2. **Publish Livewire assets:**

    ```bash
    php artisan vendor:publish --tag=livewire:assets
    ```

### Redis Setup

**Install Predis:**

1. **Install Predis:**

    ```bash
    composer require predis/predis
    ```

### Running the Application

**Serve the application:**

1. **Run the application:**

    ```bash
    php artisan serve
    ```

_The backend should now be running at: http://localhost:8000._

_This is a project for Rakso CT - Internship_
