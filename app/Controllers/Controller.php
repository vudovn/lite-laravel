<?php

namespace App\Controllers;

use Framework\Response;

class Controller
{
    protected function view($template, $data = [])
    {
        return Response::view($template, $data);
    }

    protected function json($data, $statusCode = 200)
    {
        return Response::json($data, $statusCode);
    }

    protected function redirect($uri)
    {
        return (new Response('', 302))->setHeader('Location', $uri);
    }

    // Add consistent redirect with flash message
    protected function redirectWithMessage($uri, $message, $type = 'info', $title = '')
    {
        toast($message, $type, $title);
        return $this->redirect($uri);
    }
}
