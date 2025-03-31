<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toarts Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình mặc định cho thư viện Toarts
    |
    */

    // Vị trí hiển thị thông báo
    'position' => 'top-right', // Các giá trị: top-right, top-center, top-left, bottom-right, bottom-center, bottom-left

    // Thời gian hiển thị (ms)
    'duration' => 5000,

    // Tự động đóng
    'autoClose' => true,

    // Hiển thị nút đóng
    'closeButton' => true,

    // Cho phép kéo để đóng
    'draggable' => true,

    // Hiển thị thanh tiến trình
    'progressBar' => true,

    // Tạm dừng khi hover
    'pauseOnHover' => true,

    // Hiệu ứng khi hiển thị
    'showAnimation' => 'fade', // Các giá trị: fade, slide, bounce

    // Hiệu ứng khi đóng
    'hideAnimation' => 'fade', // Các giá trị: fade, slide

    // Style mặc định cho các loại thông báo
    'types' => [
        'success' => [
            'icon' => 'fa-regular fa-circle-check',
            'title' => 'Thành công',
            'backgroundColor' => '#10B981',
            'textColor' => '#ffffff'
        ],
        'error' => [
            'icon' => 'fa-regular fa-circle-xmark',
            'title' => 'Lỗi',
            'backgroundColor' => '#EF4444',
            'textColor' => '#ffffff'
        ],
        'warning' => [
            'icon' => 'fa-regular fa-triangle-exclamation',
            'title' => 'Cảnh báo',
            'backgroundColor' => '#F59E0B',
            'textColor' => '#ffffff'
        ],
        'info' => [
            'icon' => 'fa-regular fa-circle-info',
            'title' => 'Thông tin',
            'backgroundColor' => '#3B82F6',
            'textColor' => '#ffffff'
        ]
    ]
];