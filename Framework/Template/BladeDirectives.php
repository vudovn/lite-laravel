<?php

namespace Framework\Template;

class BladeDirectives
{
    /**
     * Được sử dụng để đăng ký tất cả các directive tùy chỉnh
     * 
     * @param \Framework\Template\BladeCompiler $compiler
     * @return void
     */
    public static function register($compiler)
    {
        // Thêm các directive vào đây
        $compiler->directive('auth', function () {
            return '<?php if(auth()): ?>';
        });

        $compiler->directive('endauth', function () {
            return '<?php endif; ?>';
        });

        $compiler->directive('guest', function () {
            return '<?php if(!auth()): ?>';
        });

        $compiler->directive('endguest', function () {
            return '<?php endif; ?>';
        });

        $compiler->directive('csrf', function () {
            return '<?php echo csrf_field(); ?>';
        });

        $compiler->directive('method', function ($method) {
            return '<?php echo method_field(' . $method . '); ?>';
        });

        $compiler->directive('error', function ($field) {
            return '<?php if (has_error(' . $field . ')): ?>';
        });

        $compiler->directive('enderror', function () {
            return '<?php endif; ?>';
        });

        $compiler->directive('component', function ($expression) {
            // Simpler implementation for components
            return '<?php $__componentPath = ' . $expression . '; ob_start(); $slot = ""; ?>';
        });

        $compiler->directive('endcomponent', function () {
            return '<?php $slot = ob_get_clean(); include view_path($__componentPath . ".blade.php"); ?>';
        });
    }
}