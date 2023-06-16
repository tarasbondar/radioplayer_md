<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Models\RadioStation2Category;
use Illuminate\Http\Request;

use App\Models\RadioStationCategory;

class RadioStationCategoryController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $categories = RadioStationCategory::select("*")
            ->offset(($page - 1) * $page_size)->limit($page_size)
            ->get();
        $pagination = RadioStationCategory::paginate($page_size)->links();
        return view('pages.admin.stationcategories', ['categories' => $categories, 'pagination' => $pagination]);
    }

    public function add() {
        return view('pages.admin.stationcategories-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $category = RadioStationCategory::find($id);
        return view('pages.admin.stationcategories-edit', ['action' => 'edit', 'category' => $category]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $model = new RadioStationCategory();
        } else {
            $model = RadioStationCategory::find($request['id']);
        }
        $model->key = $request['key'];
        $model->status = $request['status'];
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

        return redirect()->action([RadioStationCategoryController::class, 'index']);
    }

    public function delete($id) {
        $category = RadioStationCategory::find($id);
        RadioStation2Category::whereRaw('category_id = ' . $id)->delete();
        $category->delete();
        return '';
    }
}
