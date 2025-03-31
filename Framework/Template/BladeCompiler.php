<?php

namespace Framework\Template;

class BladeCompiler
{
    protected $cachePath;
    protected $compiledExtension = '.php';
    protected $directives = [];

    public function __construct($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    public function compile($path)
    {
        $content = file_get_contents($path);

        // Get file name
        $filename = md5($path);
        $compiledPath = $this->cachePath . '/' . $filename . $this->compiledExtension;

        // Compile only if cached version doesn't exist or is older than template
        if (!file_exists($compiledPath) || filemtime($path) > filemtime($compiledPath)) {
            $compiled = $this->compileString($content);

            if (!is_dir($this->cachePath)) {
                mkdir($this->cachePath, 0755, true);
            }

            file_put_contents($compiledPath, $compiled);
        }

        return $compiledPath;
    }

    /**
     * Biên dịch một chuỗi Blade thành PHP
     * 
     * @param string $content
     * @return string
     */
    protected function compileString($content)
    {
        // Xóa comments Blade {{-- ... --}}
        $content = preg_replace('/\{\{--(.+?)--\}\}/s', '', $content);

        // Thêm định nghĩa biến sections để chắc chắn rằng nó tồn tại
        $content = '<?php $__sections = $__sections ?? []; ?>' . $content;

        // Biên dịch extends
        $content = preg_replace('/@extends\s*\([\'"](.*)[\'"]\)/', '<?php $__layout = \'$1\'; ?>', $content);

        // Biên dịch các statements
        $content = $this->compileStatements($content);

        // Biên dịch các echo expressions
        $content = $this->compileEchos($content);

        // Biên dịch các custom directives
        $content = $this->compileDirectives($content);

        return $content;
    }

    protected function compileStatements($content)
    {
        // @if, @elseif, @else
        $content = preg_replace('/@if\s*\((.*)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\((.*)\)/', '<?php elseif($1): ?>', $content);
        $content = preg_replace('/@else/', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

        // @foreach, @endforeach
        $content = preg_replace('/@foreach\s*\((.*)\)/', '<?php foreach($1): ?>', $content);
        $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

        // @for, @endfor
        $content = preg_replace('/@for\s*\((.*)\)/', '<?php for($1): ?>', $content);
        $content = preg_replace('/@endfor/', '<?php endfor; ?>', $content);

        // @while, @endwhile
        $content = preg_replace('/@while\s*\((.*)\)/', '<?php while($1): ?>', $content);
        $content = preg_replace('/@endwhile/', '<?php endwhile; ?>', $content);

        // @section với nội dung inline (một dòng)
        $content = preg_replace('/@section\s*\([\'"](.*)[\'"]\s*,\s*(.+)\)/', '<?php $__sections[\'$1\'] = $2; ?>', $content);

        // @section với nội dung block (nhiều dòng)
        $content = preg_replace('/@section\s*\([\'"](.*)[\'"]\)/', '<?php $__currentSection = \'$1\'; ob_start(); ?>', $content);
        $content = preg_replace('/@endsection/', '<?php $__sections[$__currentSection] = ob_get_clean(); ?>', $content);

        // Xử lý @yield từng trường hợp THEO ĐÚNG THỨ TỰ ƯU TIÊN

        // 1. @yield('name', config('app.name', 'default')) - trường hợp đặc biệt với config
        $content = preg_replace_callback(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*config\([^\)]+\)\s*\)/',
            function ($matches) {
                $pattern = '/[\'"]([^\'"]+)[\'"]\s*,\s*(config\([^\)]+\))/';
                preg_match($pattern, $matches[0], $parts);
                return '<?php echo isset($__sections[\'' . $parts[1] . '\']) ? $__sections[\'' . $parts[1] . '\'] : ' . $parts[2] . '; ?>';
            },
            $content
        );

        // 2. @yield('name', 'default string') - tham số mặc định là chuỗi
        $content = preg_replace_callback(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)/',
            function ($matches) {
                return '<?php echo isset($__sections[\'' . $matches[1] . '\']) ? $__sections[\'' . $matches[1] . '\'] : \'' . $matches[2] . '\'; ?>';
            },
            $content
        );

        // 3. @yield('name', $variable) - tham số mặc định là biến đơn giản
        $content = preg_replace_callback(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\)/',
            function ($matches) {
                return '<?php echo isset($__sections[\'' . $matches[1] . '\']) ? $__sections[\'' . $matches[1] . '\'] : ' . $matches[2] . '; ?>';
            },
            $content
        );

        // 4. @yield('name', expression) - tham số mặc định là biểu thức khác
        $content = preg_replace_callback(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*([^\'"][^\)]+)\)/',
            function ($matches) {
                return '<?php echo isset($__sections[\'' . $matches[1] . '\']) ? $__sections[\'' . $matches[1] . '\'] : (' . trim($matches[2]) . '); ?>';
            },
            $content
        );

        // 5. @yield('name') - không có tham số thứ 2
        $content = preg_replace_callback(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/',
            function ($matches) {
                return '<?php echo isset($__sections[\'' . $matches[1] . '\']) ? $__sections[\'' . $matches[1] . '\'] : \'\'; ?>';
            },
            $content
        );

        // @include
        $content = preg_replace('/@include\s*\([\'"](.*)[\'"]\)/', '<?php include view_path("$1.blade.php"); ?>', $content);

        // @component, @endcomponent
        $content = preg_replace('/@component\s*\([\'"](.*)[\'"]\)/', '<?php $__componentPath = \'$1\'; ob_start(); $slot = ""; ?>', $content);
        $content = preg_replace('/@endcomponent/', '<?php $slot = ob_get_clean(); include view_path($__componentPath . ".blade.php"); ?>', $content);

        // @slot, @endslot
        $content = preg_replace('/@slot\s*\([\'"](.*)[\'"]\)/', '<?php $__slotName = \'$1\'; ob_start(); ?>', $content);
        $content = preg_replace('/@endslot/', '<?php $$__slotName = ob_get_clean(); ?>', $content);

        // @php, @endphp
        $content = preg_replace('/@php/', '<?php', $content);
        $content = preg_replace('/@endphp/', '?>', $content);

        return $content;
    }

    protected function compileEchos($content)
    {
        // {{ $var }} - Echo content
        $content = preg_replace('/\{\{\s*(.+?)\s*\}\}/s', '<?php echo htmlspecialchars($1); ?>', $content);

        // {!! $var !!} - Raw echo content
        $content = preg_replace('/\{!!\s*(.+?)\s*!!\}/s', '<?php echo $1; ?>', $content);

        return $content;
    }

    /**
     * Đăng ký một directive tùy chỉnh
     * 
     * @param string $name
     * @param callable $handler
     * @return void
     */
    public function directive($name, $handler)
    {
        $this->directives[$name] = $handler;
    }

    /**
     * Biên dịch các directive tùy chỉnh
     * 
     * @param string $content
     * @return string
     */
    protected function compileDirectives($content)
    {
        // @Library directive - chỉ đánh dấu thư viện để tải, không hiển thị
        $content = preg_replace('/@Library\s*\([\'"](.*)[\'"]\)/', '<?php Library(\'$1\'); ?>', $content);

        // @CSS directive - hiển thị tất cả CSS
        $content = preg_replace('/@CSS/', '<?php echo app(\'library\')->getCSS(); ?>', $content);

        // @JS directive - hiển thị tất cả JS
        $content = preg_replace('/@JS/', '<?php echo app(\'library\')->getJS(); ?>', $content);

        // Các custom directives có thể được thêm vào đây

        // @error directive với block - check và hiển thị nội dung điều kiện
        $content = preg_replace_callback('/@error\s*\([\'"]([^\'"]+)[\'"]\)(.*?)@enderror/s', function ($matches) {
            return '<?php if(has_error(\'' . $matches[1] . '\')): ?>' . $matches[2] . '<?php endif; ?>';
        }, $content);

        // @error('field') directive - inline usage - hiển thị error message
        $content = preg_replace('/@error\s*\([\'"]([^\'"]+)[\'"]\)/', '<?php echo error(\'' . '$1' . '\'); ?>', $content);

        return $content;
    }

    /**
     * Kiểm tra xem view đã hết hạn hay chưa
     * 
     * @param string $path
     * @param string $compiled
     * @return bool
     */
    public function isExpired($path, $compiled)
    {
        // Luôn trả về true khi tắt cache view, buộc phải biên dịch lại
        if (config('app.view_cache', true) === false) {
            return true;
        }

        // Nếu file biên dịch không tồn tại, view đã hết hạn
        if (!file_exists($compiled)) {
            return true;
        }

        // Nếu thời gian sửa đổi của view lớn hơn thời gian sửa đổi của file biên dịch, view đã hết hạn
        return filemtime($path) >= filemtime($compiled);
    }
}
