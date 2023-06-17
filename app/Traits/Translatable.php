<?php
namespace App\Traits;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Facades\App;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'object');
    }

    public function getTranslatableAttributes()
    {
        return $this->translatable ?? [];
    }

    public function getTranslation($field, $languageCode = null)
    {
        if (!$languageCode) {
            $languageCode = App::getLocale();
        }

        $translation = $this->translations()->whereHas('language', function ($query) use ($languageCode) {
            $query->where('lang', $languageCode);
        })->first();

        if ($translation && isset($translation->{$field})) {
            return $translation->{$field};
        }
        return null;
    }

    public function setTranslation($field, $languageCode, $value)
    {
        $translation = $this->translations()->whereHas('language', function ($query) use ($languageCode) {
            $query->where('code', $languageCode);
        })->first();

        if (!$translation) {
            $translation = new Translation();
            $translation->language()->associate(Language::where('code', $languageCode)->firstOrFail());
            $this->translations()->save($translation);
        }

        $translation->{$field} = $value;
        $translation->save();
    }
}
