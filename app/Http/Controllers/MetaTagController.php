<?php

namespace App\Http\Controllers;

use App\Exports\PodcastsExport;
use App\Helpers\LanguageHelper;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\MetaTag;
use App\Models\PlaylistRecord;
use App\Models\PodcastEpisode;
use App\Models\PodcastStatRecord;
use App\Models\PodcastSub;
use App\Models\QueuedEpisode;
use Illuminate\Http\Request;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\Podcast2Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Meta;

class MetaTagController extends Controller
{

    public function index(Request $request) {
        $page_size = $request->get('page-size', 10);
        $page = $request->get('page', 1);

        $route = $request->get('route', '');

        $models = MetaTag::query();
        if (!empty($route)) {
            $models = $models->where('meta_tags.route', 'LIKE', "%{$route}%");
        }

        $pagination = $models->paginate($page_size)->withQueryString()->links();

        $models = $models->offset(($page - 1) * $page_size)->limit($page_size)
            ->get();

        return view('pages.admin.metatags', ['models' => $models, 'pagination' => $pagination]);
    }


    public function add() {
        return view('pages.admin.metatags-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $model = MetaTag::find($id);
        return view('pages.admin.metatags-edit', ['action' => 'edit', 'model' => $model]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $model = new MetaTag();
        } else {
            $model = MetaTag::find($request['id']);
        }

        $model->route = '/'.ltrim(trim($request->get('route')), '/');
        $model->is_default = (int)$request->get('is_default');

        if ($model->is_default){
            MetaTag::where('is_default', '=', 1)->update(['is_default' => 0]);
        }

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

        return redirect()->action([MetaTagController::class, 'index']);
    }


    public function delete($id) {
        $model = MetaTag::where('id', '=', $id)->first();


        $model->delete();
        return '';
    }

}
