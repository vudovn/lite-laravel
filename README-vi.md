# Lite Laravel Framework

Framework PHP nhẹ, hiệu suất cao lấy cảm hứng từ Laravel, kết hợp sự đơn giản với các tính năng mạnh mẽ cho các ứng dụng web hiện đại.

## Tính Năng Chính

- **Hệ Thống Định Tuyến Tối Ưu**: Định nghĩa route đơn giản và linh hoạt
- **Kiến Trúc MVC**: Phân tách rõ ràng giữa Model, View và Controller
- **Template Engine Hiện Đại**: Cú pháp template trực quan, tương tự Blade
- **Trừu Tượng Hóa Cơ Sở Dữ Liệu**: Query builder linh hoạt cho thao tác CSDL hiệu quả
- **Xác Thực Biểu Mẫu**: Hệ thống xác thực toàn diện với xử lý lỗi rõ ràng
- **Container Dependency Injection**: Container dịch vụ đơn giản nhưng mạnh mẽ
- **Hỗ Trợ Middleware**: Lọc và xử lý request HTTP
- **Quản Lý Session**: Xử lý session an toàn
- **Hệ Thống Xác Thực**: Xác thực tích hợp với tùy chọn tùy biến
- **Hỗ Trợ API**: Công cụ để xây dựng RESTful API dễ dàng

## Yêu Cầu Hệ Thống

- PHP 8.0 trở lên
- PDO PHP Extension
- Composer

## Cài Đặt Nhanh

```bash
composer create-project vudovn/lite-laravel ten-du-an
cd ten-du-an
php -S localhost:8000 -t public
```

Truy cập `http://localhost:8000` trong trình duyệt để xem ứng dụng mới của bạn.

## Ví Dụ Sử Dụng Cơ Bản

### Định Nghĩa Routes

```php
// routes/web.php

$router->get('/', function() {
    return view('welcome');
});

$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
```

### Tạo Controllers

```php
// app/Controllers/UserController.php

namespace App\Controllers;

use Framework\Controller;
use Framework\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = // lấy dữ liệu từ model
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email'
        ]);

        // Logic tạo user

        return redirect('/users')->with('success', 'Đã tạo người dùng thành công');
    }
}
```

### Xây Dựng Views

```php
<!-- resources/views/users/index.php -->

<h1>Danh Sách Người Dùng</h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user->name ?> (<?= $user->email ?>)</li>
    <?php endforeach; ?>
</ul>
```

### Làm Việc Với Models

```php
// app/Models/User.php

namespace App\Models;

use Framework\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    // Các phương thức bổ sung...
}
```

## Cấu Hình

Các tệp cấu hình nằm trong thư mục `config`. Sao chép `.env.example` thành `.env` và cấu hình thiết lập ứng dụng của bạn:

```
APP_NAME=LiteLaravel
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_NAME=ten_database
DB_USER=root
DB_PASS=
```

## Tài Liệu

Để xem tài liệu chi tiết, vui lòng tham khảo các nguồn sau:

- [Hướng Dẫn Framework](docs/framework-vi.md)
- [Tài Liệu API](docs/api-vi.md)
- [Sử Dụng Cơ Sở Dữ Liệu](docs/database-vi.md)
- [Template View](docs/views-vi.md)

## Cộng Đồng & Hỗ Trợ

- [GitHub Issues](https://github.com/vudovn/lite-laravel/issues)
- [Diễn Đàn Cộng Đồng](https://forum.litelaravel.com)
- [Tài Liệu](https://docs.litelaravel.com)

## Giấy Phép

Lite Laravel framework là phần mềm mã nguồn mở được cấp phép theo [Giấy phép MIT](LICENSE).
