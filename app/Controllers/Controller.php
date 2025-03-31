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

    // Updated redirect with flash message without using toast
    protected function redirectWithMessage($uri, $message, $type = 'info', $title = '')
    {
        // Store message in session using flash
        session()->flash('flash_message', [
            'type' => $type,
            'message' => $message,
            'title' => $title
        ]);

        return $this->redirect($uri);
    }
}
