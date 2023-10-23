<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Models\RadioStationTag;
use App\Models\RadioStation2Tag;
use Illuminate\Http\Request;

class RadioStationTagController extends Controller
{
    public function index(Request $request) {
        $page_size = $request->get('page-size',10);
        $page = $request->get('page', 1);

        $tags = RadioStationTag::select("*")
            ->offset(($page - 1) * $page_size)->limit($page_size)
            ->get();

        $pagination = RadioStationTag::paginate($page_size)->withQueryString()->links();

        return view('pages.admin.stationtags', ['tags' => $tags, 'pagination' => $pagination]);
    }

    public function add() {
        return view('pages.admin.stationtags-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $tag = RadioStationTag::find($id);
        return view('pages.admin.stationtags-edit', ['action' => 'edit', 'tag' => $tag]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $model = new RadioStationTag();
        } else {
            $model = RadioStationTag::find($request['id']);
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

        return redirect()->action([RadioStationTagController::class, 'index']);
    }

    public function delete($id) {
        $tag = RadioStationTag::find($id);
        RadioStation2Tag::whereRaw('tag_id = ' . $id)->delete();
        $tag->delete();
        return '';
    }
}
