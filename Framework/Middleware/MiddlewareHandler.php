<?php

namespace Framework\Middleware;

use Framework\Request;
use Framework\Response;

class MiddlewareHandler
{
    protected $middleware = [];
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
        // Lấy danh sách middleware đã đăng ký
        $this->middleware = $app->resolve('middleware') ?? [];
    }

    /**
     * Handle request through middleware pipeline
     *
     * @param  Request  $request
     * @param  \Closure  $then
     * @return Response
     */
    public function handle(Request $request, \Closure $then)
    {
        return $this->pipeline($request, $then);
    }

    /**
     * Send request through middleware pipeline
     *
     * @param  Request  $request
     * @param  \Closure  $destination
     * @return Response
     */
    protected function pipeline(Request $request, \Closure $destination)
    {
        if (empty($this->middleware)) {
            return $destination($request);
        }

        // Tạo hàm nhận request và trả về response cho middleware cuối cùng
        $firstMiddleware = $this->createInitialClosure($this->middleware, $destination);

        // Pipe request through entire middleware stack
        return $firstMiddleware($request);
    }

    /**
     * Create the initial closure that will handle the request
     *
     * @param  array  $middleware
     * @param  \Closure  $destination
     * @return \Closure
     */
    protected function createInitialClosure(array $middleware, \Closure $destination)
    {
        // Đảo ngược middleware để cho middleware cuối cùng ngoài cùng
        $middlewareStack = array_reverse($middleware);

        // Bắt đầu với destination closure
        $next = $destination;

        // Lặp qua từng middleware và bọc destination closure với middleware
        foreach ($middlewareStack as $middlewareClass) {
            $next = function ($request) use ($middlewareClass, $next) {
                $middlewareInstance = new $middlewareClass();
                return $middlewareInstance->handle($request, $next);
            };
        }

        return $next;
    }
}