# Lite Laravel Framework

A lightweight, high-performance PHP framework inspired by Laravel, combining simplicity with powerful features for modern web applications.

## Key Features

- **Streamlined Routing System**: Simple, expressive route definitions
- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Modern Template Engine**: Intuitive, Blade-like template syntax
- **Database Abstraction**: Fluent query builder for efficient database operations
- **Form Validation**: Comprehensive validation with clear error handling
- **Dependency Injection Container**: Simple yet powerful service container
- **Middleware Support**: Filter HTTP requests and responses
- **Session Management**: Secure session handling
- **Authentication System**: Built-in authentication with customization options
- **API Support**: Tools for building RESTful APIs easily

## System Requirements

- PHP 8.0 or higher
- PDO PHP Extension
- Composer

## Quick Installation

```bash
composer create-project vudovn/lite-laravel your-project-name
cd your-project-name
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser to see your new application.

## Basic Usage Examples

### Defining Routes

```php
// routes/web.php

$router->get('/', function() {
    return view('welcome');
});

$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
```

### Creating Controllers

```php
// app/Controllers/UserController.php

namespace App\Controllers;

use Framework\Controller;
use Framework\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = // fetch users from model
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email'
        ]);

        // Create user logic

        return redirect('/users')->with('success', 'User created successfully');
    }
}
```

### Building Views

```php
<!-- resources/views/users/index.php -->

<h1>Users</h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user->name ?> (<?= $user->email ?>)</li>
    <?php endforeach; ?>
</ul>
```

### Working with Models

```php
// app/Models/User.php

namespace App\Models;

use Framework\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    // Additional methods...
}
```

## Configuration

Configuration files are located in the `config` directory. Copy `.env.example` to `.env` and configure your application settings:

```
APP_NAME=LiteLaravel
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_NAME=your_database
DB_USER=root
DB_PASS=
```

## Documentation

For detailed documentation, please refer to the following resources:

- [Framework Guide](docs/framework.md)
- [API Documentation](docs/api.md)
- [Database Usage](docs/database.md)
- [View Templating](docs/views.md)

## Community & Support

- [GitHub Issues](https://github.com/vudovn/lite-laravel/issues)
- [Community Forum](https://forum.litelaravel.com)
- [Documentation](https://docs.litelaravel.com)

## License

The Lite Laravel framework is open-source software licensed under the [MIT license](LICENSE).
