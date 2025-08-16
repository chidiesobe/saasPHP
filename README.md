# SaaS PHP - Laravel React Starter Kit

A comprehensive SaaS starter kit built with Laravel, React, and Inertia.js featuring robust authentication, user management, and modern development tooling.

## 🚀 Key Features

**Authentication & Security**
- Multi-factor authentication with email and phone verification
- Social login (Google, Microsoft, Yahoo, GitHub, Twitter)
- Magic link passwordless authentication
- Two-factor authentication (2FA)
- Role-based access control with Spatie Laravel Permission
- User impersonation

**User Management**
- Filament-based admin panel with user impersonation
- Complete user verification system
- Profile management and settings

**Modern Tech Stack**
- **Backend**: Laravel with Fortify authentication
- **Frontend**: React 19 with TypeScript
- **Styling**: Tailwind CSS with Radix UI components
- **Build Tools**: Vite with hot reload
- **Testing**: Pest framework
- **Icons**: Lucide icon library

**Developer Experience**
- TypeScript for type safety
- ESLint & Prettier for code quality
- Inertia.js for SPA-like experience without API complexity
- Concurrent development processes

## 🔮 Future Plans

- **Marketplace**: Product management, shopping cart, wishlist, order tracking
- **Payments**: Stripe, PayPal, and multi-gateway support
- **Advanced Features**: Reviews, inventory management, shipping integration

## 📋 Requirements

- PHP 8.2+
- Node.js 18+
- Composer
- SQLite (default) or MySQL/PostgreSQL

## 🛠️ Installation

1. **Clone and Navigate**
```bash
git clone https://github.com/chidiesobe/saasPHP saasphp
cd saasphp
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
# Create SQLite database (default)
touch database/database.sqlite

# Run migrations and seed data
php artisan migrate
php artisan db:seed
```

5. **Build Assets**
```bash
npm run build
```

## 🚀 Development

**Quick Start (Recommended)**
```bash
composer run new
```
This single command handles migrations, seeding, and starts all development services.

```bash
composer run dev
```
For everyday coding and testing (no migrations or seeding).

**Individual Services**
```bash
php artisan serve    # Laravel server
npm run dev         # Vite dev server
php artisan queue:work  # Queue worker
php artisan pail    # Log monitoring
```

**Code Quality**
```bash
npm run format      # Format code
npm run lint        # Lint code
npm run types       # Type checking
```

## 🧪 Testing

```bash
php artisan test    # Run all tests
php artisan test --coverage  # With coverage
php artisan test tests/Feature/Auth/AuthenticationTest.php  # Specific test
```

## 🔑 Default Users (First Install)

When you run `composer run new` for the first time, the system will automatically create the following default accounts:

| Email               | Password   | Role  |
|---------------------|------------|-------|
| `admin@saasphp.com` | `password` | Admin |
| `user1@saasphp.com` | `password` | User  |
| `user2@saasphp.com` | `password` | User  |

> ⚠️ **Security Notice**  
> These default accounts are for **development and testing only**.  
> Before deploying to production, you should:
> - Change the default passwords.  
> - Update the admin email.  
> - Remove or disable the sample user accounts if not needed.


## 📁 Project Structure

```
app/
├── Actions/Fortify/     # Authentication actions
├── Filament/           # Admin panel resources
├── Http/Controllers/   # Application controllers
├── Models/            # Eloquent models
└── Services/          # Business logic

resources/
├── js/
│   ├── components/    # React components
│   ├── pages/        # Inertia.js pages
│   └── layouts/      # Layout components
└── views/            # Blade templates

routes/
├── web.php           # Web routes
├── auth.php         # Authentication routes
└── settings.php     # Settings routes
```

## ⚙️ Configuration

**Key Environment Variables**
```env
APP_NAME="SaaS PHP"
APP_URL=http://localhost
DB_CONNECTION=sqlite
```

**Admin Panel**
Access the Filament admin panel at `/admin` for user management, roles, permissions, and site settings.

**SMS Integration**
Configure SMS providers (Africa's Talking or Vonage) in the admin panel for phone verification.

## 🚀 Production Deployment

```bash
# Optimize for production
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

Ensure production environment variables are configured for database, social login providers, and SMS services.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Run tests and linting
4. Submit a pull request


## 🆘 Support

- Check documentation and existing issues
- Create new issues for bugs or feature requests

---

Built with ❤️ using Laravel, React, and modern web technologies