<?php

namespace App\Http\Controllers;

use App\Models\RadioStation;
use App\Models\RadioStationCategory;
use App\Models\RadioStationFavorite;
use App\Models\RadioStationGroup;
use App\Models\RadioStationStatRecord;
use App\Models\RadioStationTag;
use App\Models\RadioStation2Category;
use App\Models\RadioStation2Tag;
use App\Models\StationFavHistoryRecord;
use App\Services\RadioApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
//use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RadioStationsExport;

class RadioStationController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $name = $request->get('name', '');
        $descr = $request->get('descr', '');
        $appends = [];

        $stations = RadioStation::select("*");
        if (!empty($name)) {
            $stations = $stations->where('name', 'LIKE', "%{$name}%");
            $appends['name'] = $name;
        }
        if (!empty($descr)) {
            $stations = $stations->where('description', 'LIKE', "%{$descr}%");
            $appends['descr'] = $descr;
        }
        if ($page > 1) {
            $appends['page'] = $page;
        }

        $pagination = $stations->paginate($page_size)->appends($appends)->links();

        $stations = $stations->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();

        return view('pages.admin.radiostations', ['stations' => $stations, 'pagination' => $pagination, 'params' => $appends]);
    }

    public function stats(Request $request) {
        $id = $request->get('id', '');
        $from = $request->get('from', Carbon::yesterday());
        $to = $request->get('to', Carbon::now());

        $stats_plays = RadioStationStatRecord::from('radiostations_history AS rh')
            ->selectRaw('rs.id, rs.name, COUNT(rh.station_id) AS plays')
            ->join('radiostations AS rs', 'rh.station_id', '=', 'rs.id');

        $stats_favs_total = RadioStationFavorite::from('radiostations_favorites AS rf')
            ->selectRaw('rs.id, rs.name,  COUNT(rf.station_id) AS favs_total')
            ->join('radiostations AS rs', 'rf.station_id', '=', 'rs.id');

        $stats_favs_history = StationFavHistoryRecord::from('radiostations_favorites_history AS sfh')
            ->selectRaw('rs.id, rs.name,  COUNT(sfh.station_id) AS favs')
            ->join('radiostations AS rs', 'sfh.station_id', '=', 'rs.id');

        if (!empty($id)) {
            $stats_plays = $stats_plays->where('rs.id', '=', $id);
            $stats_favs_total = $stats_favs_total->where('rs.id', '=', $id);
            $stats_favs_history = $stats_favs_history->where('rs.id', '=', $id);
        }

        if (!empty($from)) {
            $stats_plays = $stats_plays->where('click_time', '>=', $from . ' 00:00:00');
            $stats_favs_total = $stats_favs_total->where('rf.created_at', '>=', $from . ' 00:00:00');
            $stats_favs_history = $stats_favs_history->where('sfh.created_at', '>=', $from . ' 00:00:00');
        }

        if (!empty($to)) {
            $stats_plays = $stats_plays->where('click_time', '<=', $to . ' 23:59:59');
            $stats_favs_total = $stats_favs_total->where('rf.created_at', '<=', $to . ' 23:59:59');
            $stats_favs_history = $stats_favs_history->where('sfh.created_at', '<=', $to . ' 23:59:59');
        }

        $stats_plays = $stats_plays->groupBy('rs.id')
            ->get()->toArray();

        $stats_favs_total = $stats_favs_total->groupBy('rs.id')
            ->get()->toArray();

        $stats_favs = $stats_favs_history->where('sfh.action', '=', StationFavHistoryRecord::ACTION_FAV)->groupBy('rs.id')
            ->get()->toArray();

        $stats_unfavs = $stats_favs_history->where('sfh.action', '=', StationFavHistoryRecord::ACTION_UNFAV)->groupBy('rs.id')
            ->get()->toArray();

        $stats = [];
        foreach ($stats_plays as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['plays'] = $stat['plays'];
        }

        foreach ($stats_favs_total as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['favs_total'] = $stat['favs_total'];
        }

        foreach ($stats_favs as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['favs'] = $stat['favs'];
        }

        foreach ($stats_unfavs as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['unfavs'] = $stat['unfavs'];
        }

        return view('pages.admin.stations-stats', ['stats' => $stats]);
    }

    public function add() {
        $categories = RadioStationCategory::where('status', '=', RadioStationCategory::STATUS_ACTIVE)->get(['id', 'key']);
        $tags = RadioStationTag::where('status', '=', RadioStationTag::STATUS_ACTIVE)->get(['id', 'key']);
        $groups = RadioStationGroup::all()->toArray();

        return view('pages.admin.radiostations-edit', [
            'action' => 'add',
            'categories' => $categories,
            's2c' => [],
            'tags' => $tags,
            's2t' => [],
            'groups' => $groups,
            'apis' => RadioApiService::$apiLabels
        ]);
    }

    public function edit($id) {
        $station = RadioStation::find($id)->toArray();
        $categories = RadioStationCategory::where('status', '=', RadioStationCategory::STATUS_ACTIVE)->get(['id', 'key']);
        $s2c = $this->getCategoriesByStation($id);
        $tags = RadioStationTag::where('status', '=', RadioStationTag::STATUS_ACTIVE)->get(['id', 'key']);
        $s2t = $this->getTagsByStation($id);
        $groups = RadioStationGroup::all()->toArray();
        return view('pages.admin.radiostations-edit', [
            'action' => 'edit',
            'station' => $station,
            'categories' => $categories,
            's2c' => $s2c,
            'tags' => $tags,
            's2t' => $s2t,
            'groups' => $groups,
            'apis' => RadioApiService::$apiLabels
        ]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $station = new RadioStation();
            $categories_current = [];
            $tags_current = [];
        } else {
            $station = RadioStation::find($request['id']);
            $categories_current = $this->getCategoriesByStation($request['id']);
            $tags_current = $this->getTagsByStation($station->id);
        }
        $station->name = $request['name'];
        $station->description = $request['description'];
        $station->group_id = $request['group-id'];
        $station->source = $request['source'];
        $station->source_hd = $request['source-hd'];
        $station->source_meta = $request['source-meta'];
        $station->api_id = $request['api_id'];

        if ($request->has('image')) {
            if (isset($station->image_logo)) {
                File::delete(RadioStation::UPLOADS_IMAGES . '/' . $station->image_logo);
            }
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(RadioStation::UPLOADS_IMAGES, $filename);
            $station->image_logo = $filename;
        }

        $station->status = $request['status'];
        $station->order = $request->get('order', 1);
        $station->save();

        $categories_new = explode(',', $request['categories']);

        if (array_diff($categories_new, $categories_current)) {
            $to_add = array_filter(array_diff($categories_new, $categories_current));
            if (count($to_add)) {
                foreach ($to_add as $category_id) {
                    RadioStation2Category::create(['station_id' => $station->id, 'category_id' => $category_id, 'created_at' => now()]);
                }
            }
        }

        if (array_diff($categories_current, $categories_new)) {
            $to_delete = array_filter(array_diff($categories_current, $categories_new));
            if (count($to_delete)) {
                $ids = implode(', ', $to_delete);
                RadioStation2Category::whereRaw("station_id = {$station->id} AND category_id IN ($ids)")->delete();
            }
        }

        //tags
        $tags_new = explode(',', $request['tags']);

        if (array_diff($tags_new, $tags_current)) {
            $to_add = array_filter(array_diff($tags_new, $tags_current));
            if (count($to_add)) {
                foreach ($to_add as $tag_id) {
                    RadioStation2Tag::create(['station_id' => $station->id, 'tag_id' => $tag_id, 'created_at' => now()]);
                }
            }
        }

        if (array_diff($tags_current, $tags_new)) {
            $to_delete = array_filter(array_diff($tags_current, $tags_new));
            if (count($to_delete)) {
                $ids = implode(', ', $to_delete);
                RadioStation2Tag::whereRaw("station_id = {$station->id} AND tag_id IN ($ids)")->delete();
            }
        }


        return redirect()->action([RadioStationController::class, 'index']);
    }

    public function delete($id) {
        $station = RadioStation::find($id);
        RadioStation2Category::whereRaw('station_id = ' . $id)->delete();
        RadioStation2Tag::whereRaw('tag_id = ' . $id)->delete();
        if (!empty($station->image)) {
            unlink(RadioStation::UPLOADS_IMAGES . '/' . $station->image);
        }
        $station->delete();
        return '';
    }

    public function download(Request $request) {
        $name = $request->get('name', '');
        $descr = $request->get('descr', '');
        return Excel::download(new RadioStationsExport($name, $descr), '1.xlsx');
    }

    public function getCategoriesByStation($station_id) {
        $query = DB::table('radiostations_categories AS rc')
            ->leftJoin('radiostations_2_categories AS r2c','rc.id', '=', 'r2c.category_id')
            ->where('r2c.station_id', '=', $station_id)
            ->where('rc.status', '=', RadioStationCategory::STATUS_ACTIVE)
            ->orderBy('rc.id', 'ASC')
            ->get('rc.id')
            ->toArray();
        $result = [];
        foreach ($query as $q) {array_push($result, $q->id);}
        return $result;
    }

    public function getTagsByStation($station_id) {
        $query = DB::table('radiostations_tags AS rt')
            ->leftJoin('radiostations_2_tags AS r2t','rt.id', '=', 'r2t.tag_id')
            ->where('r2t.station_id', '=', $station_id)
            ->where('rt.status', '=', RadioStationTag::STATUS_ACTIVE)
            ->orderBy('rt.id', 'ASC')
            ->get('rt.id')
            ->toArray();
        $result = [];
        foreach ($query as $q) {array_push($result, $q->id);}
        return $result;
    }

}
