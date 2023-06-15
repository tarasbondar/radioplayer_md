<?php

namespace App\Helpers;

use App\Models\MetaTag;
use getID3;
use Illuminate\Http\Request;

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

    public static function getMetaTitle(){
        // get current route
        $request = Request::capture();
        $path = $request->path();
        $model = MetaTag::where('route', '/'.$path)->first();
        $text = __('app.appTitle');
        if (!$model)
            $model = MetaTag::where('is_default', 1)->first();
        if ($model){
            $modelText = $model->getTranslation('meta_title');
            if (!empty($modelText))
                $text = $modelText;
        }
        return $text;
    }
    public static function getMetaKeywords(){
        // get current route
        $request = Request::capture();
        $path = $request->path();
        $model = MetaTag::where('route', '/'.$path)->first();
        $text = __('app.appSeoKeywords');
        if (!$model)
            $model = MetaTag::where('is_default', 1)->first();
        if ($model){
            $modelText = $model->getTranslation('meta_keywords');
            if (!empty($modelText))
                $text = $modelText;
        }
        return $text;
    }
    public static function getMetaDescription(){
        // get current route
        $request = Request::capture();
        $path = $request->path();
        $model = MetaTag::where('route', '/'.$path)->first();
        $text = __('app.appSeoDescription');
        if (!$model)
            $model = MetaTag::where('is_default', 1)->first();
        if ($model){
            $modelText = $model->getTranslation('meta_description');
            if (!empty($modelText))
                $text = $modelText;
        }
        return $text;
    }
}
