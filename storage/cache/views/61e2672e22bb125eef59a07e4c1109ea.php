<?php $__sections = $__sections ?? []; ?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($__sections['title']) ? $__sections['title'] : config('app.name', 'LitePHP'); ?></title>
    <!-- CSS cơ bản -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo htmlspecialchars(asset('/library/fontAwesome6Pro/css/all.min.css')); ?>" rel="stylesheet">
    <!-- Toarts CSS -->
    <link href="<?php echo htmlspecialchars(asset('/library/toarts/toarts.min.css')); ?>" rel="stylesheet">
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
    <?php echo app('library')->getCSS(); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php echo isset($__sections['styles']) ? $__sections['styles'] : ''; ?>
</head>

<body>
    <div class="container mt-5">
        <?php echo isset($__sections['content']) ? $__sections['content'] : ''; ?>
    </div>

    <!-- Scripts cơ bản -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toarts JS -->
    <script src="<?php echo htmlspecialchars(asset('/library/toarts/toarts.min.js')); ?>"></script>
    <!-- Kiểm tra và tạo toast thủ công nếu cần -->
    <!-- Tự động load JavaScript từ thư viện -->
    <?php echo app('library')->getJS(); ?>
    <!-- Scripts tùy chỉnh -->
    <?php echo isset($__sections['scripts']) ? $__sections['scripts'] : ''; ?>

    <!-- Script hiển thị toast notifications -->
    <?php echo toasts_script(); ?>

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
