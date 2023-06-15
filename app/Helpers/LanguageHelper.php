<?php
namespace App\Helpers;

use App\Models\Language;

class LanguageHelper
{
    public static function getLanguages($status = Language::STATUS_ALL)
    {
        $query = Language::query();

        if ($status !== Language::STATUS_ALL) {
            $query->where('status', $status);
        }

        return $query->orderBy('sort', 'asc')->get();
    }
}

