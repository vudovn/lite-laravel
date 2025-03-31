<?php

namespace App\Middleware;

class FlashSessionMiddleware
{
    /**
     * Xử lý request thông qua middleware
     *
     * @param  \Framework\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Thực hiện request và lấy response
        $response = $next($request);
        // Lưu flash data để chuẩn bị cho request tiếp theo
        session()->save();
        return $response;
    }
}