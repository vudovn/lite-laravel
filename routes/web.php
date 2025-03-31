<?php

use Framework\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});


// Route test toast với redirect để sử dụng flash session
Route::get('/test-toast', function () {
    // Kiểm tra nếu session chưa được khởi tạo
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    toast_warning('Đây là thông báo cảnh báo test', 'Cảnh báo');
    return redirect('/');
});

// Route để xóa cache view
Route::get('/clear-view-cache', function () {
    $files = glob(storage_path('cache/views/*'));
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    toast_success('Cache view đã được xóa', 'Thành công');
    return redirect($_SERVER['HTTP_REFERER'] ?? '/');
});

