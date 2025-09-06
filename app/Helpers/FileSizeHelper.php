<?php

namespace App\Helpers;

class FileSizeHelper
{
    public static function formatFileSize($bytes, $decimals = 2)
    {
        if ($bytes < 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        if ($factor == 0) {
            $decimals = 0;
        }

        $size = $bytes / pow(1024, $factor);
        return sprintf("%.{$decimals}f %s", $size, $units[$factor]);
    }
}
