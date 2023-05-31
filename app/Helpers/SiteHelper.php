<?php

namespace App\Helpers;

use getID3;

class SiteHelper
{
    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    public static function getMp3Duration($filePath): int
    {
        $getID3 = new GetID3;

        // Analyze the MP3 file
        $fileInfo = $getID3->analyze($filePath);

        // Check if duration information is available
        if (isset($fileInfo['playtime_seconds'])) {
            $duration = $fileInfo['playtime_seconds'];
            return $duration;
            // Format the duration as HH:MM:SS or MM:SS

            //return ($duration >= 3600) ? gmdate("H:i:s", $duration) : gmdate("i:s", $duration);
        }

        return 0; // Duration information not found
    }

    public static function getFormattedDuration($duration = 0): string
    {
        return gmdate("H:i:s", $duration);
    }
}
