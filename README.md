# Lite Laravel

A lightweight PHP framework inspired by Laravel, providing core features like routing, MVC pattern, template engine, database abstraction, and more.

## Features

- **Simple Routing**: Define routes with closures or controller methods
- **MVC Architecture**: Organize your code with Models, Views, and Controllers
- **Template Engine**: Simple but powerful template system
- **Database Abstraction**: Query builder for database operations
- **Form Validation**: Validate user input with ease
- **Dependency Injection**: Simple container for resolving dependencies
- **Middleware Support**: Filter HTTP requests
- **Session Management**: Store and retrieve user data
- **Authentication**: Simple auth system
- **Simple API**: Build RESTful APIs easily

## Requirements

- PHP 7.4+
- PDO PHP Extension
- Composer

## Installation

```bash
composer create-project litelaravel/framework project-name
cd project-name
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser to see the welcome page.

## Basic Usage

### Routes

Define routes in `routes/web.php`:

```php
// routes/web.php

$router->get('/', function() {
    return view('welcome');
});

$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
```

### Controllers

```php
// app/Controllers/UserController.php

namespace App\Controllers;

use Framework\Controller;
use Framework\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = // fetch users
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email'
        ]);

        // Create user

        return redirect('/users');
    }
}
```

### Views

```php
// resources/views/users/index.php

<h1>Users</h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user->name ?> (<?= $user->email ?>)</li>
    <?php endforeach; ?>
</ul>
```

### Models

```php
// app/Models/User.php

namespace App\Models;

use Framework\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    // Methods...
}
```

## Configuration

Configuration files are stored in the `config` directory. Copy `.env.example` to `.env` and adjust the settings to match your environment.

```
APP_NAME=LiteLaravel
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_NAME=database
DB_USER=root
DB_PASS=
```

## Documentation

For more detailed documentation, see the [Documentation](docs/index.md).

## License

The Lite Laravel framework is open-sourced software licensed under the [MIT license](LICENSE).
