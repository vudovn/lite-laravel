<?php

namespace Framework\Template;

class BladeCompiler
{
    protected $cachePath;
    protected $compiledExtension = '.php';

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

    protected function compileString($content)
    {
        // Compile blade directives
        $content = $this->compileEchos($content);
        $content = $this->compileStatements($content);

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

        // @include
        $content = preg_replace('/@include\s*\(\'(.*)\'\)/', '<?php include \'$1.blade.php\'; ?>', $content);

        // @php, @endphp
        $content = preg_replace('/@php/', '<?php', $content);
        $content = preg_replace('/@endphp/', '?>', $content);

        return $content;
    }
}
