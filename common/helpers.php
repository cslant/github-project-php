<?php

declare(strict_types=1);

if (!function_exists('color_value_format')) {
    /**
     * Format a value with an optional color using LaTeX syntax for GitHub Markdown.
     */
    function color_value_format(string $value, ?string $color = null): string
    {
        if ($color === null || $color === '') {
            return $value;
        }

        return '$${\color{'.$color.'}'.$value.'}$$';
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date string using Carbon.
     */
    function format_date(?string $date, string $format = 'Y-m-d'): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception) {
            return $date;
        }
    }
}
