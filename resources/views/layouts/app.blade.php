<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'LitePHP'))</title>
    <!-- CSS cơ bản -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('/library/fontAwesome6Pro/css/all.min.css') }}" rel="stylesheet">
    <!-- Toarts CSS -->
    <link href="{{ asset('/library/toarts/toarts.min.css') }}" rel="stylesheet">
    <!-- Toast animation CSS -->
    <style>
        @keyframes toarts-fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toarts-slide {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toarts-bounce {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.6;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
    <!-- Tự động load CSS từ thư viện -->
    @CSS
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('styles')
</head>

<body>
    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Scripts cơ bản -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toarts JS -->
    <script src="{{ asset('/library/toarts/toarts.min.js') }}"></script>
    <!-- Kiểm tra và tạo toast thủ công nếu cần -->
    <!-- Tự động load JavaScript từ thư viện -->
    @JS
    <!-- Scripts tùy chỉnh -->
    @yield('scripts')

    <!-- Script hiển thị toast notifications -->
    {!! toasts_script() !!}

    <script>
        // Check if Toarts is loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Toarts === 'undefined') {
                console.error('Toarts library not loaded. Please check your script includes.');
            } else {
                console.log('Toarts library loaded successfully.');
            }
        });
    </script>
</body>

</html>
