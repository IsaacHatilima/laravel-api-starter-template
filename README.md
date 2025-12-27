# Laravel API Starter Template

A robust, opinionated Laravel API starter kit built for high code quality and scalability.

## 🚀 Core Tech Stack

* **Authentication:
  ** [Fortify](https://laravel.com/docs/fortify) & [JWT-Auth](https://github.com/php-open-source-saver/jwt-auth)
* **Social Auth:** [Socialite](https://laravel.com/docs/socialite)
* **Code Quality:** * **Pint**: PHP code style fixer.
    * **PHPStan**: Static analysis (Strict Level).
    * **PHP Insights**: Architecture and complexity analysis.

## 📂 File Structure

This project uses a custom architecture to keep logic decoupled and testable:

* **app/Actions**: Single-responsibility business logic classes.
* **app/DTOs**: Data Transfer Objects for type-safe data handling.
* **app/Repositories**: Abstracted data access layer.
* **app/Jobs**: Background processing.
* **app/Traits**: Reusable cross-concern logic.

## 🔐 2FA Implementation (Scan Guide)

Since this is a headless API, follow these steps to scan your Authenticator QR code:

1. Hit the **2FA Enable** route to generate your secret and recovery codes.
2. Copy the `qr_code` (SVG string) from the JSON response.
3. Paste that SVG string into `resources/views/welcome.blade.php`.
4. Open your app's home URL in a browser.
5. Scan the rendered QR code with Google Authenticator or Authy.

## 🏁 Installation

```bash
composer run setup
```

## 🛠 Development Commands

Run these to maintain the 100/100 quality score:

```bash
# Fix code style
./vendor/bin/pint

# Run static analysis
./vendor/bin/phpstan analyse --level=10 app/ --configuration=phpstan.neon.dist

# Check quality and complexity
php artisan insights
```

