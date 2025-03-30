# VuToiChoi Framework

Framework PHP nhẹ, đơn giản và mạnh mẽ cho các ứng dụng web hiện đại. VuToiChoi lấy cảm hứng từ Laravel nhưng tập trung vào hiệu suất cao và dễ sử dụng.

## Tính năng

- **Nhẹ và nhanh**: Được thiết kế tối ưu hiệu suất với codebase nhỏ gọn
- **MVC Architecture**: Mô hình Model-View-Controller rõ ràng và dễ hiểu
- **Routing linh hoạt**: Hỗ trợ các HTTP methods, route parameters, route groups
- **Blade-like Templates**: Hệ thống template linh hoạt với cú pháp tương tự Blade
- **Validation**: Hệ thống validation mạnh mẽ và dễ sử dụng
- **Database Integration**: Tích hợp dễ dàng với cơ sở dữ liệu
- **Session & Cache**: Quản lý session và cache đơn giản
- **Helper Functions**: Nhiều hàm helper để phát triển nhanh chóng

## Cài đặt

### Yêu cầu

- PHP 7.4 hoặc cao hơn
- Composer

### Cài đặt thông qua Composer

```bash
composer create-project vutoichoi/framework ten-du-an
```

### Cấu hình cơ bản

Sao chép file `.env.example` thành `.env` và cấu hình các thông số môi trường:

```bash
cp .env.example .env
```

Chỉnh sửa file `.env` để cấu hình database và các thông số khác:

```bash
APP_NAME=VuToiChoi
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

## Bắt đầu nhanh chóng

### 1. Tạo route đầu tiên

```php
// routes/web.php
$router->get('/', function () {
    return view('welcome');
});

$router->get('/hello', function () {
    return 'Xin chào từ VuToiChoi Framework!';
});
```

### 2. Tạo controller

```php
// app/Controllers/UserController.php
namespace App\Controllers;

use Framework\Request;

class UserController
{
    public function index(Request $request)
    {
        // Lấy tất cả users (đây chỉ là ví dụ)
        $users = [
            ['id' => 1, 'name' => 'Nguyễn Văn A'],
            ['id' => 2, 'name' => 'Trần Thị B'],
        ];

        return view('users.index', ['users' => $users]);
    }
}
```

### 3. Tạo view

```php
// resources/views/users/index.php
<!DOCTYPE html>
<html>
<head>
    <title>Danh sách người dùng</title>
</head>
<body>
    <h1>Danh sách người dùng</h1>

    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo $user['name']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```

### 4. Chạy ứng dụng

```bash
cd ten-du-an
php -S localhost:8000 -t public
```

Mở trình duyệt và truy cập `http://localhost:8000`

## Routing

### Các HTTP Methods

```php
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');
```

### Route với Closure

```php
$router->get('/welcome', function () {
    return 'Xin chào VuToiChoi!';
});
```

### Route với Parameters

```php
$router->get('/users/{id}', function ($request) {
    $id = $request->param('id');
    return "Người dùng có ID: {$id}";
});
```

### Route Groups

```php
$router->group(['prefix' => 'admin'], function ($router) {
    $router->get('/dashboard', 'AdminController@dashboard');
    $router->get('/users', 'AdminController@users');
});
```

### Resource Routes

```php
$router->resource('users', 'UserController');
```

## Controllers

```php
namespace App\Controllers;

use Framework\Request;

class UserController
{
    public function index(Request $request)
    {
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
        ]);

        // Lưu dữ liệu

        return redirect('/users');
    }
}
```

## Views

```php
// Render view đơn giản
return view('home', ['title' => 'Trang chủ']);

// Với layout
// resources/views/users/show.php
<?php $this->setLayout('layouts.app'); ?>

<div class="user-profile">
    <h2><?php echo $user['name']; ?></h2>
    <p>Email: <?php echo $user['email']; ?></p>
</div>
```

## Request Handling

```php
// Lấy tất cả dữ liệu
$data = $request->all();

// Lấy một trường cụ thể
$email = $request->get('email');

// Kiểm tra trường tồn tại
if ($request->has('email')) {
    // Xử lý email
}

// Validate dữ liệu
$validatedData = $request->validate([
    'name' => 'required|min:3',
    'email' => 'required|email',
]);
```

## Database

```php
// Lấy kết nối database
$db = app('db')->connection();

// Query
$users = $db->query("SELECT * FROM users")->fetchAll();

// Prepared statement
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
```

## Tài liệu đầy đủ

Xem tài liệu đầy đủ tại thư mục `docs/` trong dự án hoặc truy cập trang web tài liệu:

- [Tài liệu Framework](docs/framework-vi.md)
- [API Documentation](docs/api-vi.md)

## Đóng góp

Chúng tôi rất hoan nghênh mọi đóng góp để cải thiện framework! Vui lòng tạo pull request hoặc báo cáo issues trên GitHub.

## License

VuToiChoi Framework được phát hành dưới [giấy phép MIT](LICENSE).
