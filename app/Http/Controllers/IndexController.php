<?php


namespace App\Http\Controllers;

use App\Models\DownloadRecord;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\PodcastEpisode;
use App\Models\PodcastSub;
use App\Models\RadioStation;
use App\Models\RadioStationCategory;
use App\Models\RadioStationTag;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class IndexController
{

    public function __construct() {
        $lang = Auth::check() ? Auth::user()->language : 'en';
        view()->share(['lang' => $lang]);
    }

    /**
     * Home page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request) {
        $category_id = $request->get('category_id', 0);
        $tag_id = $request->get('tag_id', 0);

        $stations = Arr::keyBy($this->searchStations($category_id, $tag_id), 'id');

        $tags = RadioStationTag::select('*')->where('status', '=', RadioStationTag::STATUS_ACTIVE)->get()->toArray();
        $categories = RadioStationCategory::select('*')->where('status', '=', RadioStationCategory::STATUS_ACTIVE)->get()->toArray();

        $fav_stations = FavoriteService::getFavorites();

        return view('pages.client.index', ['stations' => $stations, 'tags' => $tags, 'categories' => $categories, 'fav_stations' => $fav_stations]);
    }

    /**
     * Update stations
     *
     * @param Request $request
     * @return string
     */
    public function updateStations(Request $request) {
        if (!$request->ajax()) {
            exit;
        }

        $category_id = $request->get('category_id', 0);
        $tag_id = $request->get('tag_id', 0);
        $stations = $this->searchStations($category_id, $tag_id);
        $fav_stations = FavoriteService::getFavorites();
        $output = '';
        foreach ($stations as $station) {
            $output .= view('partials.station-card', ['station' => $station, 'fav_stations' => $fav_stations])->render();
        }
        return $output;
    }

    public function searchStations($category_id = 0, $tag_id = 0, $group_id = 0) {
        $user_id = (Auth::check() ? Auth::id() : 0);
        $stations = RadioStation::select(DB::raw("rs.*, if ((SELECT user_id FROM radiostations_favorites WHERE user_id = {$user_id} AND station_id = rs.id), 1, 0 ) as `favorited`"))
            ->from('radiostations AS rs')
            ->leftJoin('radiostations_2_categories AS r2c', 'r2c.station_id', '=', 'rs.id')
            ->leftJoin('radiostations_2_tags AS r2t', 'r2t.station_id', '=', 'rs.id')
            ->leftJoin('radiostations_favorites AS rf', 'rf.station_id', '=', 'rs.id')
            ->where('rs.status', '=', RadioStation::STATUS_ACTIVE);

        if ($category_id > 0) {
            $stations = $stations->where('r2c.category_id', '=', $category_id);
        }

        if ($tag_id > 0) {
            $stations = $stations->where('r2t.tag_id', '=', $tag_id);
        }

        if ($group_id > 0) {
            $stations = $stations->where('rs.group_id', '=', $group_id);
        }

        $stations = $stations->orderBy('order', 'DESC')->distinct()->get()->toArray();

        return $stations;
    }

    /**
     * Play radio station
     *
     * @param $id
     * @return string
     */
    public function playStation($id) {
        $current = RadioStation::where(['id' => $id])->first()->toArray();
        if ($current['status'] == RadioStation::STATUS_INACTIVE) {
            return '';
        }

        $favorited = in_array($current['id'], FavoriteService::getFavorites());

        $all = [];
        if ($current['group_id'] != 0) {
            $all = $this->searchStations(0, 0, $current['group_id']);
        }

        return view('partials.player-radio', ['current' => $current, 'all' => $all, 'favorited' => $favorited])->render();
    }

    public function allPodcasts(Request $request) {
        $page_size = 5;
        $podcasts = Podcast::where('status', '=', Podcast::STATUS_ACTIVE)->limit(5)->get()->toArray(); //$this->searchPodcasts($request->get('name', ''), $request->get('categories', ''));
        $episodes =
            PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
                ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
                ->where('p.status', '=', Podcast::STATUS_ACTIVE)
                ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
                ->orderBy('updated_at', 'DESC');
        $page_count = ceil($episodes->count() / $page_size);
        $episodes = $episodes->limit($page_size)->get()->toArray();
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get()->toArray();
        return view('pages.client.all-podcasts', ['podcasts' => $podcasts, 'categories' => $categories, 'episodes' => $episodes, 'page_count' => $page_count]);
    }

    public function allSearch(Request $request) { //text, categories
        $page_size = 5;
        $podcasts = Podcast::where('status', '=', Podcast::STATUS_ACTIVE);

        $categories = $request->get('categories', []);
        $text = $request->get('text', '');

        //podcasts
        if (!empty($categories)) {
            $podcasts = $podcasts->join('podcasts_2_categories AS p2c', 'p2c.podcast_id', '=', 'podcasts.id')->whereRaw("p2c.category_id IN ($categories)");
        }

        if (strlen($text) > 5) {
            $podcasts = $podcasts->where('name', 'LIKE', '%'.$text.'%')
            ->orWhere('description', 'LIKE', '%'.$text.'%')
            ->orWhere('tags', 'LIKE', '%'.$text.'%');
        }

        $podcasts = $podcasts->distinct()->limit(5)->get()->toArray();

        $podcasts_render = '';
        foreach ($podcasts as $p) {
            $podcasts_render .= view('partials.podcast-card', ['p' => $p])->render();
        }

        //episodes
        $episodes = PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
            ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
            ->where('p.status', '=', Podcast::STATUS_ACTIVE)
            ->join('podcasts AS p', 'p.id', '=', 'podcasts_episodes.podcast_id');

        if (!empty($categories)) {
            $episodes = $episodes->join('podcasts_2_categories AS p2c', 'p2c.podcast_id', '=', 'p.id')->whereRaw("p2c.category_id IN ($categories)");
        }

        if (strlen($text) > 3) {
            $episodes = $episodes->where('podcasts_episodes.name', 'LIKE', '%'.$text.'%')
                ->orWhere('podcasts_episodes.description', 'LIKE', '%'.$text.'%')
                ->orWhere('podcasts_episodes.tags', 'LIKE', '%'.$text.'%');
        }

        $episodes = $episodes->distinct('podcasts_episodes.id')->orderBy('updated_at', 'DESC');
        $page_count = ceil($episodes->count() / $page_size);
        $episodes = $episodes->limit($page_size)->get()->toArray();

        $episodes_render = '';
        foreach ($episodes as $episode) {
            $episodes_render .= view('partials.episode-card', ['episode' => $episode])->render();
        }

        return ['podcasts' => $podcasts_render, 'episodes' => $episodes_render, 'page_count' => $page_count];

    }

    public function appendEpisodes(Request $request) {
        $page_size = 5;
        $categories = $request->get('categories', []);
        $text = $request->get('text', '');
        $page = $request->get('page', 2);

        $episodes = PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
            ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
            ->where('p.status', '=', Podcast::STATUS_ACTIVE)
            ->join('podcasts AS p', 'p.id', '=', 'podcasts_episodes.podcast_id');

        if (!empty($categories)) {
            $episodes = $episodes->join('podcasts_2_categories AS p2c', 'p2c.podcast_id', '=', 'p.id')->whereRaw("p2c.category_id IN ($categories)");
        }

        if (strlen($text) > 3) {
            $episodes = $episodes->where('podcasts_episodes.name', 'LIKE', '%'.$text.'%')
                ->orWhere('podcasts_episodes.description', 'LIKE', '%'.$text.'%')
                ->orWhere('podcasts_episodes.tags', 'LIKE', '%'.$text.'%');
        }

        $episodes = $episodes->distinct('podcasts_episodes.id')->orderBy('updated_at', 'DESC')->offset(($page - 1) * $page_size)->limit($page_size)->get()->toArray();

        $episodes_render = '';
        foreach ($episodes as $episode) {
            $episodes_render .= view('partials.episode-card', ['episode' => $episode])->render();
        }

        return $episodes_render;
    }


    public function podcasts(Request $request) {
        $podcasts = $this->searchPodcasts($request->get('name', ''), $request->get('categories', ''));
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get()->toArray();
        return view('pages.client.podcasts', ['podcasts' => $podcasts, 'categories' => $categories]);
    }

    public function updatePodcasts(Request $request) {
        if (!$request->ajax()) {
            exit;
        }

        $podcasts = $this->searchPodcasts($request->get('name', ''), $request->get('categories', ''));
        $output = '';
        foreach ($podcasts as $podcast) {
            $output .= view('partials.podcast-card', ['p' => $podcast])->render();
        }
        return $output;
    }

    public function searchPodcasts($name = '', $categories = '') {
        $page_size = 12;
        $search_name = $name;
        $search_categories = $categories;
        $podcasts = Podcast::where('status', '=', Podcast::STATUS_ACTIVE);
        if (!empty($search_categories)) {
            $podcasts = $podcasts->join('podcasts_2_categories AS p2c', 'p2c.podcast_id', '=', 'podcasts.id')->whereRaw("p2c.category_id IN ({$search_categories})");
        }
        if (!empty($search_name)) {
            $podcasts = $podcasts->whereRaw("name LIKE '%{$search_name}%'");
        }
        $podcasts = $podcasts->distinct()->limit($page_size)->orderBy('updated_at', 'desc')->get()->toArray();
        return $podcasts;
    }

    public function viewPodcast($id) {
        $podcast = Podcast::select('podcasts.*', 'users.name AS username')
            ->join('users', 'podcasts.owner_id', '=', 'users.id')
            ->where('podcasts.id', '=', $id)->first()->toArray();

        if (Auth::id() == $podcast['owner_id'] || $podcast['status'] == Podcast::STATUS_ACTIVE) {
            if(!Auth::check() ) {
                $action = 'sub';
            } else {
                if (Auth::id() == $podcast['owner_id']) {
                    $action = 'edit';
                } else {
                    $sub = PodcastSub::where('user_id', '=', Auth::id())->where('podcast_id', '=', $id)->get();
                    if (count($sub)) {
                        $action = 'unsub';
                    } else {
                        $action = 'sub';
                    }
                }
            }


            $episodes = PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id');
            if ($action != 'edit') {
                $episodes = $episodes
                    ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
                    ->where('p.status', '=', Podcast::STATUS_ACTIVE);
            }
            $episodes = $episodes->where('p.id', '=', $id)
                ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
                ->orderBy('updated_at', 'DESC')
                ->get()->toArray();
            return view('pages.client.podcast-episodes', ['podcast' => $podcast, 'episodes' => $episodes, 'action' => $action]);
        }

        return abort(403);
    }

    public function viewEpisode($id) {
        $episode = PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
                ->where('podcasts_episodes.id', '=', $id)
                ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
                ->orderBy('updated_at', 'DESC')
                ->first()->toArray();
        $podcast = Podcast::select('podcasts.*', 'users.name AS username')
            ->join('users', 'podcasts.owner_id', '=', 'users.id')
            ->where('podcasts.id', '=', $episode['podcast_id'])->first()->toArray();
        if ($podcast['owner_id'] != Auth::id() && ($episode['status'] != PodcastEpisode::STATUS_PUBLISHED || $podcast['status'] != Podcast::STATUS_ACTIVE)) {
            return abort(403);
        }
        /*$sub = PodcastSub::where('user_id', '=', Auth::id())->where('podcast_id', '=', $podcast['id'])->count();
        $podcast['subbed'] = $sub;*/
        return view('pages.client.episode-inner', ['episode' => $episode, 'podcast' => $podcast]);
    }

    public function playEpisode($id){
        $current = PodcastEpisode::find($id);
        if (!$current)
            return '';

        if ($current->status != PodcastEpisode::STATUS_PUBLISHED) {
            return '';
        }
        $podcast = $current->podcast;
        if ($podcast->status == Podcast::STATUS_INACTIVE) {
            return '';
        }
        /*if (Auth::check()) {
            $favorited = DB::table('radiostations_favorites')->where('user_id', '=', Auth::id())->where('station_id', '=', $current['id'])->count();
        } else {
            $favorited = 0;
        }*/

        $episodes = $this->searchPodcasts();

        return view('partials.player-podcasts', ['current' => $current, 'episodes' => $episodes, 'podcast' => $podcast])->render();
    }

    public function downloadEpisode(Request $request){
        $episode = PodcastEpisode::where('id', '=', $request->get('id'))->first();
        $podcast = $episode->podcast;
        if ($episode->status < PodcastEpisode::STATUS_PUBLISHED || $podcast->status == Podcast::STATUS_INACTIVE) {
            return abort(403);
        }
        $file = public_path(PodcastEpisode::UPLOADS_AUDIO . "/" . $episode->source);
        return response()->download($file);
    }

    public function getDownloadData(Request $request) {
        $episode = PodcastEpisode::where('id', '=', $request->get('id'))->first();
        $record = new DownloadRecord();
        $user_id = (Auth::check() ? Auth::id() : 0 );
        $record->user_id = $user_id;
        $record->episode_id = $episode->id;
        $record->save();
        return $episode->filename;
    }

    public function privacy() {
        return view('');
    }

    /**
     * Add or remove station from favorites
     *
     * @param $id
     * @return array
     */
    public function favStation($id) {
        if (!Auth::check()) {
            return FavoriteService::favStationForUnregisteredUser($id);
        }
        return FavoriteService::favStationForRegisteredUser($id);
    }
}
