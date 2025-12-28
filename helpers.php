<?php

if (! function_exists('human_filesize')) {
    function human_filesize($size, $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }

        return round($size, $precision).' '.$units[$i];
    }
}

if (! function_exists('extension_from_path')) {
    function extension_from_path(string $url): ?string
    {
        $extension = null;
        foreach ([PHP_URL_PATH, PHP_URL_QUERY] as $part) {
            $extension = pathinfo(parse_url($url, $part), PATHINFO_EXTENSION);
            if (! empty($extension)) {
                break;
            }
        }

        return $extension ? ".{$extension}" : null;
    }
}
