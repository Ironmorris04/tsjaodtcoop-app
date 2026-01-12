<?php

if (!function_exists('storage_url')) {
    /**
     * Generate URL for storage files that works with LocalTunnel
     *
     * @param string|null $path
     * @return string
     */
    function storage_url($path = null)
    {
        if ($path === null || $path === '') {
            return '';
        }

        // Remove any leading slashes to ensure consistent paths
        $path = ltrim($path, '/');

        return route('image.serve', ['path' => $path]);
    }
}
