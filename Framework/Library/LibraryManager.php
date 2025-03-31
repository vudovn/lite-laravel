<?php

namespace Framework\Library;

class LibraryManager
{
    protected $libraries = [];
    protected $loadedCSS = [];
    protected $loadedJS = [];
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->registerDefaultLibraries();

        // Tự động load tất cả thư viện mặc định
        $this->loadAllLibraries();
    }

    /**
     * Đăng ký các thư viện mặc định
     */
    protected function registerDefaultLibraries()
    {
        // Đăng ký Font Awesome Pro
        $this->registerLibrary('font-awesome', [
            'css' => [
                asset('/library/fontAwesome6Pro/css/all.min.css'),
            ],
            'js' => [
                asset('/library/fontAwesome6Pro/js/pro.min.js'),
            ]
        ]);

        // Đăng ký Toarts
        $this->registerLibrary('toarts', [
            'css' => [
                asset('/library/toarts/toarts.min.css'),
            ],
            'js' => [
                asset('/library/toarts/toarts.min.js'),
            ]
        ]);
    }

    /**
     * Tự động load tất cả thư viện đã đăng ký
     */
    public function loadAllLibraries()
    {
        foreach (array_keys($this->libraries) as $library) {
            $this->libraries[$library]['load'] = true;
        }
    }

    /**
     * Đăng ký một thư viện
     * 
     * @param string $name
     * @param array $assets
     * @return void
     */
    public function registerLibrary($name, array $assets)
    {
        $this->libraries[$name] = $assets;
    }

    /**
     * Lấy tất cả các CSS của thư viện đã đăng ký và được đánh dấu để tải
     * 
     * @return string
     */
    public function getCSS()
    {
        $html = '';

        foreach ($this->libraries as $name => $assets) {
            // Chỉ lấy CSS của thư viện đã được đánh dấu để tải
            if (!isset($assets['load']) || $assets['load'] !== true) {
                continue;
            }

            if (isset($assets['css'])) {
                foreach ($assets['css'] as $css) {
                    if (!in_array($css, $this->loadedCSS)) {
                        $html .= '<link href="' . $css . '" rel="stylesheet">' . PHP_EOL;
                        $this->loadedCSS[] = $css;
                    }
                }
            }
        }

        return $html;
    }

    /**
     * Lấy tất cả các JS của thư viện đã đăng ký và được đánh dấu để tải
     * 
     * @return string
     */
    public function getJS()
    {
        $html = '';

        foreach ($this->libraries as $name => $assets) {
            // Chỉ lấy JS của thư viện đã được đánh dấu để tải
            if (!isset($assets['load']) || $assets['load'] !== true) {
                continue;
            }

            if (isset($assets['js'])) {
                foreach ($assets['js'] as $js) {
                    if (!in_array($js, $this->loadedJS)) {
                        $html .= '<script src="' . $js . '"></script>' . PHP_EOL;
                        $this->loadedJS[] = $js;
                    }
                }
            }
        }

        return $html;
    }

    /**
     * Đánh dấu một thư viện là sẽ được tải
     * 
     * @param string $name
     * @return void
     */
    public function load($name)
    {
        if (isset($this->libraries[$name])) {
            $this->libraries[$name]['load'] = true;
        }
    }

    /**
     * Kiểm tra xem thư viện có được đăng ký chưa
     * 
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->libraries[$name]);
    }
}