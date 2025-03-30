<?php

namespace App\Middleware;

/**
 * Middleware for injecting alerts into responses
 */
class AlertMiddleware
{
    /**
     * Handle the incoming request
     *
     * @param mixed $request The request object
     * @param \Closure $next The next middleware
     * @return mixed
     */
    public function handle($request, $next)
    {
        $response = $next($request);

        // Only inject into HTML responses
        if (has_alerts() && $this->isHtmlResponse($response)) {
            $alertsHtml = alerts_html();

            // Insert before closing body tag
            $content = $response->getContent();
            $content = str_replace('</body>', $alertsHtml . '</body>', $content);

            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Check if response is HTML
     *
     * @param mixed $response
     * @return bool
     */
    private function isHtmlResponse($response)
    {
        $contentType = $response->getHeader('Content-Type');
        return strpos($contentType, 'text/html') !== false ||
            empty($contentType) && is_string($response->getContent()) &&
            (strpos($response->getContent(), '<html') !== false ||
                strpos($response->getContent(), '<!DOCTYPE html') !== false);
    }
}