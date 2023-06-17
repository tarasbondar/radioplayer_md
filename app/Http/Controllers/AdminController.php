<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Models\CustomValue;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct(){
        //$this->middleware('is.admin');
    }

    public function dashboard(Request $request) {
        $stats = [];
        /*$stats['user_count'] = User::where('role', '=', User::ROLE_USER)->count();
        $stats['author_count'] = User::where('role', '=', User::ROLE_AUTHOR)->count();
        $stats['active_podcasts'] = Podcast::where('status', '=', Podcast::STATUS_ACTIVE)->count();
        $stats['published_episodes'] = PodcastEpisode::where('status', '=', PodcastEpisode::STATUS_PUBLISHED)->count();*/
        return view('pages.admin.dashboard', ['stats' => $stats]);
    }

    public function customValues() {
        $values = CustomValue::all();
        return view('pages.admin.customvalues', ['values' => $values]);
    }

    public function addValue() {
        return view('pages.admin.customvalues-edit', ['action' => 'add']);
    }

    public function editValue($id) {
        $value = CustomValue::find($id);
        return view('pages.admin.customvalues-edit', ['action' => 'edit', 'value' => $value]);
    }

    public function saveValue(Request $request) {
        if (empty($request->get('id'))) {
            $model = new CustomValue();
        } else {
            $model = CustomValue::find($request->get('id'));
        }
        $model->key = $request->get('key');
        $model->value = $request->get('value');
        $model->save();

        // save translations
        $languages = LanguageHelper::getLanguages();
        foreach ($model->getTranslatableAttributes() as $attribute) {
            foreach ($languages as $language) {
                $translationKey = $attribute.'_'.$language->code;
                $translationValue = $request->input($translationKey);

                if (!empty($translationValue)) {
                    $model->setTranslation($attribute, $language->code, $translationValue);
                }
            }
        }

        return redirect()->action([AdminController::class, 'customValues']);
    }

    public function deleteValues($id) {
        $value = CustomValue::find($id);
        $value->delete();
        return '';
    }

}
