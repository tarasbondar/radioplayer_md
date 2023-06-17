<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Models\PodcastCategory;
use App\Models\Podcast2Category;
use Illuminate\Http\Request;

class PodcastCategoryController extends Controller
{

    public function index(Request $request) {
        $categories = PodcastCategory::all();
        return view('pages.admin.podcastcategories', ['categories' => $categories]);
    }

    public function add() {
        return view('pages.admin.podcastcategories-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $category = PodcastCategory::find($id);
        return view('pages.admin.podcastcategories-edit', ['action' => 'edit', 'category' => $category]);
    }

    public function save(Request $request) {
        if (empty($request->get('id'))) {
            $model = new PodcastCategory();
        } else {
            $model = PodcastCategory::find($request->get('id'));
        }
        $model->key = $request->get('key');
        $model->status = $request->get('status');
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

        return redirect()->action([PodcastCategoryController::class, 'index']);
    }

    public function delete($id) {
        $category = PodcastCategory::find($id);
        Podcast2Category::whereRaw('category_id = ' . $id)->delete();
        $category->delete();
        return '';
    }

}
