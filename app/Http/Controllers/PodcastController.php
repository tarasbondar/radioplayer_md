<?php

namespace App\Http\Controllers;

use App\Exports\PodcastsExport;
use App\Helpers\LanguageHelper;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\PlaylistRecord;
use App\Models\PodcastEpisode;
use App\Models\PodcastStatRecord;
use App\Models\PodcastSub;
use App\Models\QueuedEpisode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\Podcast2Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class PodcastController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $name = $request->get('name', '');
        $descr = $request->get('descr', '');
        $appends = [];

        $podcasts = Podcast::select("podcasts.*", "users.name AS username")->join('users', 'users.id', '=', 'podcasts.owner_id');
        if (!empty($name)) {
            $podcasts = $podcasts->where('podcasts.name', 'LIKE', "%{$name}%");
            $appends['name'] = $name;
        }
        if (!empty($descr)) {
            $podcasts = $podcasts->where('podcasts.description', 'LIKE', "%{$descr}%");
            $appends['descr'] = $descr;
        }
        if ($page > 1) {
            $appends['page'] = $page;
        }


        $pagination = $podcasts->paginate($page_size)->appends($appends)->links();

        $podcasts = $podcasts->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();

        return view('pages.admin.podcasts', ['podcasts' => $podcasts, 'pagination' => $pagination]);
    }

    public function stats(Request $request) {
        $from = $request->get('from', Carbon::yesterday());
        $to = $request->get('to', Carbon::now());

        $stats = [];

        $stats_plays = PodcastStatRecord::from('podcasts_history AS ph')
            ->selectRaw('p.id, p.name, COUNT(ph.episode_id) AS plays')
            ->join('podcasts_episodes AS pe', 'ph.episode_id', '=', 'pe.id')
            ->join('podcasts AS p', 'pe.podcast_id', '=', 'p.id');

        $stats_later = QueuedEpisode::from('users_queues AS ll')
            ->selectRaw('p.id, p.name, COUNT(ll.episode_id) AS later')
            ->join('podcasts_episodes AS pe', 'll.episode_id', '=', 'pe.id')
            ->join('podcasts AS p', 'pe.podcast_id', '=', 'p.id');

        $stats_downloads = DownloadRecord::from('users_downloads AS ud')
            ->selectRaw('p.id, p.name, COUNT(ud.episode_id) AS downloads')
            ->join('podcasts_episodes AS pe', 'ud.episode_id', '=', 'pe.id')
            ->join('podcasts AS p', 'pe.podcast_id', '=', 'p.id');

        $stats_subs = PodcastSub::from('podcasts_subscriptions AS ps')
            ->selectRaw('p.id, p.name, COUNT(ps.podcast_id) AS subs')
            ->join('podcasts AS p', 'ps.podcast_id', '=', 'p.id');
        //stats subs
        //stats unsubs


        if (!empty($from)) {
            $stats_plays = $stats_plays->where('click_time', '>=', $from . ' 00:00:00');
            $stats_later = $stats_later->where('ll.created_at', '>=', $from . ' 00:00:00');
            $stats_downloads = $stats_downloads->where('ud.created_at', '>=', $from . ' 00:00:00');
            $stats_subs = $stats_subs->where('ps.created_at', '>=', $from . ' 00:00:00');
        }

        if (!empty($to)) {
            $stats_plays = $stats_plays->where('click_time', '<=', $to . ' 23:59:59');
            $stats_later = $stats_later->where('ll.created_at', '<=', $to . ' 23:59:59');
            $stats_downloads = $stats_downloads->where('ud.created_at', '<=', $to . ' 23:59:59');
            $stats_subs = $stats_subs->where('ps.created_at', '<=', $to . ' 23:59:59');
        }

        $stats_plays = $stats_plays->groupBy('p.id')
            ->get()->toArray();

        $stats_later = $stats_later->groupBy('p.id')
            ->get()->toArray();

        $stats_downloads = $stats_downloads->groupBy('p.id')
            ->get()->toArray();

        $stats_subs = $stats_subs->groupBy('p.id')
            ->get()->toArray();

        foreach ($stats_plays as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['plays'] = $stat['plays'];
        }

        foreach ($stats_later as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['later'] = $stat['later'];
        }

        foreach ($stats_downloads as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['downloads'] = $stat['downloads'];
        }

        foreach ($stats_subs as $stat) {
            $stats[$stat['id']]['name'] = $stat['name'];
            $stats[$stat['id']]['subs'] = $stat['subs'];
        }

        return view('pages.admin.podcasts-stats', ['stats' => $stats]);
    }

    public function add() {
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get(['id', 'key']);
        return view('pages.admin.podcasts-edit', ['action' => 'add', 'categories' => $categories]);
    }

    public function edit($id) {
        $podcast = Podcast::find($id);
        //categories that belong to podcast
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get(['id', 'key']);
        return view('pages.admin.podcasts-edit', ['action' => 'edit', 'podcast' => $podcast, 'categories' => $categories]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $podcast = new Podcast();
            $podcast->owner_id = Auth::id();
            $categories_current = [];
        } else {
            $podcast = Podcast::find($request['id']);
            //$podcast->owner_id = $request->get('owner_id');
            $categories_current = $this->getCategoriesByPodcast($podcast->id);
        }

        $podcast->name = $request->get('name');
        $podcast->description = $request->get('description');
        $podcast->status = $request->get('status');
        $podcast->tags = $request->get('tags');
        if ($request->has('image')) {
            if (isset($podcast->image)) {
                File::delete(Podcast::UPLOADS_IMAGES . '/' . $podcast->image);
            }
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(Podcast::UPLOADS_IMAGES, $filename);
            $podcast->image = $filename;
        }

        $podcast->save();

        // save translations
        $languages = LanguageHelper::getLanguages();
        foreach ($podcast->getTranslatableAttributes() as $attribute) {
            foreach ($languages as $language) {
                $translationKey = $attribute.'_'.$language->code;
                $translationValue = $request->input($translationKey);

                if (!empty($translationValue)) {
                    $podcast->setTranslation($attribute, $language->code, $translationValue);
                }
            }
        }


        //categories
        $categories_new = $request->get('categories', []);
        if (array_diff($categories_new, $categories_current)) {
            $to_add = array_filter(array_diff($categories_new, $categories_current));
            if (count($to_add)) {
                foreach ($to_add as $category_id) {
                    Podcast2Category::create(['podcast_id' => $podcast->id, 'category_id' => $category_id, 'created_at' => now()]);
                }
            }
        }

        if (array_diff($categories_current, $categories_new)) {
            $to_delete = array_filter(array_diff($categories_current, $categories_new));
            if (count($to_delete)) {
                $ids = implode(', ', $to_delete);
                Podcast2Category::whereRaw("podcast_id = {$podcast->id} AND category_id IN ($ids)")->delete();
            }
        }

        return redirect()->action([PodcastController::class, 'index']);
    }

    public function download(Request $request) {
        $name = $request->get('name', '');
        $descr = $request->get('descr', '');
        return Excel::download(new PodcastsExport($name, $descr), '1.xlsx');
    }

    public function delete($id) {
        $podcast = Podcast::where('id', '=', $id)->first();

        //delete episodes
        $episodes = PodcastEpisode::where('podcast_id', '=', $podcast->id)->get();
        if ($episodes->count() > 0) {
            foreach ($episodes as $e) {
                unlink(PodcastEpisode::UPLOADS_AUDIO . '/' . $e->source);
                DownloadRecord::where('episode_id', '=', $e->id)->delete();
                QueuedEpisode::where('episode_id', '=', $e->id)->delete();
                HistoryRecord::where('episode_id', '=', $e->id)->delete();
                PlaylistRecord::where('episode_id', '=', $e->id)->delete();
                $e->delete();
            }
        }

        if (!empty($podcast->image)) {
            unlink(Podcast::UPLOADS_IMAGES . '/' . $podcast->image);
        }

        Podcast2Category::where('podcast_id', '=', $id)->delete();
        PodcastSub::where('podcast_id', '=', $id)->delete();

        $podcast->delete();
        return '';
    }

    public function getCategoriesByPodcast($podcast_id) {
        $query = DB::table('podcasts_categories AS pc')
            ->leftJoin('podcasts_2_categories AS p2c','pc.id', '=', 'p2c.category_id')
            ->where('p2c.podcast_id', '=', $podcast_id)
            ->where('pc.status', '=', PodcastCategory::STATUS_ACTIVE)
            ->orderBy('pc.id', 'ASC')
            ->get('pc.id')
            ->toArray();
        $result = [];
        foreach ($query as $q) {array_push($result, $q->id);}
        return $result;
    }

}
