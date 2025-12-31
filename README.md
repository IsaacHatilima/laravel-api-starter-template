# Laravel API Starter Template

A robust, opinionated Laravel API starter kit built for high code quality and scalability.

## 🚀 Core Tech Stack

* **Authentication:
  ** [Fortify](https://laravel.com/docs/fortify) & [JWT-Auth](https://github.com/php-open-source-saver/jwt-auth)
* **Code Quality:** * **Pint**: PHP code style fixer.
    * **PHPStan**: Static analysis (Strict Level).
    * **PHP Insights**: Architecture and complexity analysis.

## 📂 File Structure

This project uses a custom architecture to keep logic decoupled and testable:

```text
app
├── Actions
│   └── V1
│       ├── Auth
│       │   ├── EmailVerificationAction.php
│       │   ├── EndAllSessionsAction.php
│       │   ├── LoginUserAction.php
│       │   ├── LogoutUserAction.php
│       │   ├── RefreshTokenAction.php
│       │   ├── RegisterUserAction.php
│       │   ├── ResetPasswordAction.php
│       │   ├── SendResetPasswordLinkAction.php
│       │   ├── TwoFactorLoginAction.php
│       │   └── VerifyResetPasswordAction.php
│       └── Settings
│           ├── ConfirmTwoFactorAction.php
│           ├── DeleteProfileAction.php
│           ├── DisableTwoFactorAction.php
│           ├── EnableTwoFactorAction.php
│           ├── GenerateTwoFactorRecoveryCodesAction.php
│           ├── ProfileUpdateAction.php
│           └── UpdatePasswordAction.php
├── DTOs
│   ├── BaseDTO.php
│   └── V1
│       ├── Command
│       │   ├── Auth
│       │   │   ├── ForgotPasswordRequestDTO.php
│       │   │   ├── LoginRequestDTO.php
│       │   │   ├── RegisterRequestDTO.php
│       │   │   └── ResetPasswordRequestDTO.php
│       │   └── Settings
│       │       ├── ChangePasswordRequestDTO.php
│       │       └── ProfileUpdateRequestDTO.php
│       └── Read
│           └── User
│               ├── AuthResponseDTO.php
│               ├── ProfileDTO.php
│               ├── TwoFactorAuthDTO.php
│               └── UserDTO.php
├── Http
│   ├── Controllers
│   │   ├── Controller.php
│   │   └── V1
│   │       ├── Auth
│   │       │   ├── EmailVerificationController.php
│   │       │   ├── EndAllSessionsController.php
│   │       │   ├── ForgotPasswordController.php
│   │       │   ├── LoginController.php
│   │       │   ├── LogoutController.php
│   │       │   ├── MeController.php
│   │       │   ├── RefreshTokenController.php
│   │       │   ├── RegisterController.php
│   │       │   ├── ResetPasswordController.php
│   │       │   └── TwoFactorLoginController.php
│   │       └── Settings
│   │           ├── DeleteProfileController.php
│   │           ├── TwoFactorManagerController.php
│   │           ├── UpdatePasswordController.php
│   │           └── UpdateProfileController.php
│   └── Requests
│       └── V1
│           ├── Auth
│           │   ├── CurrentPasswordRequest.php
│           │   ├── ForgotPasswordRequest.php
│           │   ├── LoginRequest.php
│           │   ├── RegisterRequest.php
│           │   ├── ResetPasswordRequest.php
│           │   └── TwoFactorCodeRequest.php
│           └── Settings
│               ├── ChangePasswordRequest.php
│               └── ProfileUpdateRequest.php
├── Jobs
│   └── SendVerificationEmailJob.php
├── Listeners
│   └── RevokeUserSessionsOnPasswordReset.php
├── Models
│   ├── Profile.php
│   └── User.php
├── Notifications
│   ├── ResetPasswordNotification.php
│   └── VerifyEmailWithPublicId.php
├── Repositories
│   └── UserRepository.php
└── Traits
    ├── ApiResponser.php
    ├── HasPublicUuid.php
    └── InteractsWithAuth.php
```

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

Run these for code quality, expectation is all green with all 100% for PHPInsights:

```bash
# Fix code style
./vendor/bin/pint

# Run static analysis
./vendor/bin/phpstan analyse --level=10 app/ --configuration=phpstan.neon.dist

# Check quality and complexity
php artisan insights
```

