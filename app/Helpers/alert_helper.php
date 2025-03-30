<?php

/**
 * Alert/Toast notification helper functions
 */

if (!function_exists('alert')) {
    /**
     * Create a new alert/toast notification
     *
     * @param string $type The type of alert (success, error, info, warning)
     * @param string $message The message to display
     * @param array $options Additional options for the alert
     * @return void
     */
    function alert($type, $message, array $options = [])
    {
        $session = app()->session;

        // Get existing alerts or initialize empty array
        $alerts = $session->get('_alerts', []);

        // Add the new alert
        $alerts[] = [
            'type' => $type,
            'message' => $message,
            'options' => $options
        ];

        // Store back in session
        $session->set('_alerts', $alerts);
    }
}

if (!function_exists('get_alerts')) {
    /**
     * Get all stored alerts and clear them from session
     *
     * @return array
     */
    function get_alerts()
    {
        $session = app()->session;

        // Get existing alerts
        $alerts = $session->get('_alerts', []);

        // Clear alerts from session
        $session->remove('_alerts');

        return $alerts;
    }
}

if (!function_exists('has_alerts')) {
    /**
     * Check if there are any alerts in the session
     *
     * @return bool
     */
    function has_alerts()
    {
        $session = app()->session;
        $alerts = $session->get('_alerts', []);

        return !empty($alerts);
    }
}

if (!function_exists('alerts_html')) {
    /**
     * Generate the HTML/JS for displaying alerts
     *
     * @return string
     */
    function alerts_html()
    {
        if (!has_alerts()) {
            return '';
        }

        $alerts = get_alerts();
        $config = config('app.alerts', []);

        // Default configuration
        $defaultConfig = [
            'duration' => 3000,
            'position' => 'top-right',
            'close' => true,
            'theme' => 'light'
        ];

        // Merge with user configuration
        $config = array_merge($defaultConfig, $config);

        ob_start();
        ?>
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                <?php foreach ($alerts as $alert): ?>
                    Toastify({
                        text: "<?= htmlspecialchars($alert['message'], ENT_QUOTES) ?>",
                        duration: <?= $alert['options']['duration'] ?? $config['duration'] ?>,
                        close: <?= ($alert['options']['close'] ?? $config['close']) ? 'true' : 'false' ?>,
                        gravity: "<?= strpos($alert['options']['position'] ?? $config['position'], 'top') !== false ? 'top' : 'bottom' ?>",
                        position: "<?= strpos($alert['options']['position'] ?? $config['position'], 'left') !== false ? 'left' : (strpos($alert['options']['position'] ?? $config['position'], 'center') !== false ? 'center' : 'right') ?>",
                        className: "toast-<?= $alert['type'] ?>",
                        style: {
                            background: "<?= get_toast_background_color($alert['type']) ?>",
                        },
                        onClick: <?= $alert['options']['onClick'] ?? 'function(){}' ?>
                    }).showToast();
                <?php endforeach; ?>
            });
        </script>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('get_toast_background_color')) {
    /**
     * Get background color for toast type
     *
     * @param string $type Alert type
     * @return string
     */
    function get_toast_background_color($type)
    {
        switch ($type) {
            case 'success':
                return 'linear-gradient(to right, #00b09b, #96c93d)';
            case 'error':
                return 'linear-gradient(to right, #ff5f6d, #ffc371)';
            case 'warning':
                return 'linear-gradient(to right, #f9d423, #ff4e50)';
            case 'info':
            default:
                return 'linear-gradient(to right, #2193b0, #6dd5ed)';
        }
    }
}