<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\AuthorApplication;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\PlaylistRecord;
use App\Models\Podcast;
use App\Models\Podcast2Category;
use App\Models\PodcastCategory;
use App\Models\PodcastEpisode;
use App\Models\PodcastSub;
use App\Models\QueuedEpisode;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Throwable;
use function PHPUnit\Runner\render;

class ProfileController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function index()
    {
        //return view('home');
    }

    public function apply() {
        $role = (Auth::user())->role;
        if ($role == User::STATUS_NORMAL) {
            $app = AuthorApplication::where('user_id', '=', Auth::id())->orderBy('id', 'desc')->first();

            if (!empty($app)) {
                if ($app->status == AuthorApplication::STATUS_PENDING) {
                    return view('pages.client.author-application', ['status' => 'pending']);
                }
                if ($app->status == AuthorApplication::STATUS_DECLINED) {
                    return view('pages.client.author-application', ['status' => 'declined', 'feedback' => $app->feedback_message]);
                }
                if ($app->status == AuthorApplication::STATUS_NO_RETRY) {
                    return view('pages.client.author-application', ['status' => 'no_retry', 'feedback' => $app->feedback_message]);
                }
            }
        } else { //already author
            return redirect()->action([ProfileController::class, 'myPodcasts']);
        }
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get()->toArray();
        return view('pages.client.author-application', ['status' => 'new', 'user_role' => $role,'categories' => $categories]);
    }

    public function sendApplication(Request $request) : RedirectResponse {
        $validator = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'categories-ids' => ['required'],
            'tags' => ['max:255'],
            'image' => ['required', 'image'],
            'audio' => ['required', 'file'],
            'privacy' => ['required']
        ]);

        /*if ($validator->fails()) {
            //\Log::info($validator);
            return $validator->errors();
        }*/

        $app = new AuthorApplication();
        $app->user_id = Auth::id();
        $app->title = $request->get('title');
        $app->description = $request->get('description');
        $app->categories = $request->get('categories-ids');
        $app->tags = $request->get('tags');

        $filename = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move(AuthorApplication::UPLOADS_IMAGES, $filename);
        $app->image = $filename;

        $filename = time() . '_' . $request->audio->getClientOriginalName();
        $request->audio->move(AuthorApplication::UPLOADS_AUDIO, $filename);
        $app->example = $filename;

        $app->status = AuthorApplication::STATUS_PENDING;
        $app->save();

        return redirect()->action([ProfileController::class, 'apply']);
    }

    public function myPodcasts() {
        $podcasts = Podcast::where('owner_id', '=', Auth::id())->get()->toArray();
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get()->toArray();
        return view('pages.client.my-podcasts', ['podcasts' => $podcasts, 'categories' => $categories]);
    }


    public function myPodcastsSearch(Request $request) {
        //$page_size = 12;
        $user = Auth::user();
        if ($user->status == User::STATUS_BLOCKED) {
            return abort(403);
        }

        $search_name = $request->get('name');
        $search_categories = $request->get('categories');

        $podcasts = Podcast::join('users', 'podcasts.owner_id', '=', 'users.id');
        if (!empty($search_categories)) {
            $podcasts = $podcasts->join('podcasts_2_categories AS p2c', 'p2c.podcast_id', '=', 'podcasts.id')->whereRaw("p2c.category_id IN ({$search_categories})");
        }
        if (!empty($search_name)) {
            $podcasts = $podcasts->whereRaw("podcasts.name LIKE '%{$search_name}%'");
        }

        $podcasts = $podcasts->where('podcasts.owner_id', '=', Auth::id())
            ->distinct()->orderBy('podcasts.updated_at', 'desc')->get()->toArray();

        $podcasts_render = '';

        foreach ($podcasts as $p) {
            $podcasts_render .= view('partials.podcast-card', ['p' => $p])->render();
        }
        return $podcasts_render;
    }


    public function createPodcast() {
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get(['id', 'key']);
        return view('pages.client.create-podcast', ['categories' => $categories, 'action' => 'add', 'p2c' => []]);
    }

    public function editPodcast($id) {
        $podcast = Podcast::find($id)->toArray();
        $categories = PodcastCategory::where('status', '=', PodcastCategory::STATUS_ACTIVE)->get(['id', 'key']);
        $podcast2category = $this->getCategoriesByPodcast($id);
        return view('pages.client.create-podcast', ['action' => 'edit', 'podcast' => $podcast, 'categories' => $categories, 'p2c' => $podcast2category]);
    }

    public function savePodcast(Request $request) {

        if (empty($request->get('id'))) {
            $podcast = new Podcast();
            $podcast->owner_id = Auth::id();
            $categories_current = [];
        } else {
            $podcast = Podcast::find($request->get('id'));

            if (Auth::id() != $podcast->owner_id) {
                return abort(403);
            }
            $categories_current = array_keys($this->getCategoriesByPodcast($podcast->id));
        }
        $podcast->name = $request->get('name');
        $podcast->description = $request->get('description');
        if ($request->has('image')) {
            if (isset($podcast->image)) {
                File::delete(Podcast::UPLOADS_IMAGES . '/' . $podcast->image);
            }
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(Podcast::UPLOADS_IMAGES, $filename);
            $podcast->image = $filename;
        }
        $podcast->tags = $request->get('tags');
        $podcast->status = ($request->get('status') != null ? Podcast::STATUS_ACTIVE : Podcast::STATUS_INACTIVE);
        $podcast->save();

        //categories
        $categories_new = explode(',', $request->get('categories-ids', []));
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

        return redirect()->action([ProfileController::class, 'myPodcasts']);
    }

    public function deletePodcast(Request $request) {
        $id = $request->get('id');
        $podcast = Podcast::where('id', '=', $id)->first();

        if ($podcast->owner_id != Auth::id()) {
            return abort(403);
        }

        $episodes = PodcastEpisode::where('podcast_id', '=', $podcast->id)->get();
        if ($episodes->count() > 0) {
            foreach ($episodes as $e) {
                if (!empty($e->source)) {
                    unlink(PodcastEpisode::UPLOADS_AUDIO . '/' . $e->source);
                }
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

    public function getCategoriesByPodcast($id) {
        $query = DB::table('podcasts_categories AS pc')
            ->leftJoin('podcasts_2_categories AS p2c','pc.id', '=', 'p2c.category_id')
            ->where('p2c.podcast_id', '=', $id)
            ->where('pc.status', '=', PodcastCategory::STATUS_ACTIVE)
            ->orderBy('pc.id', 'ASC')
            ->get(['pc.id', 'pc.key'])
            ->toArray();
        $result = [];
        foreach ($query as $q) {
            //array_push($result, $q->id);
            $result[$q->id] = $q->key;
        }
        return $result;
    }

    public function createEpisode($podcast_id) {
        $podcast = Podcast::find($podcast_id);
        if (Auth::user()->role < User::ROLE_AUTHOR || Auth::id() != $podcast->owner_id) {
            return abort(403);
        }
        return view('pages.client.create-episode', ['action' => 'add', 'podcast_id' => $podcast_id]);
    }

    public function editEpisode($id) {
        $episode = PodcastEpisode::where('id', '=', $id)->first();
        if (Auth::id() != Podcast::find($episode['podcast_id'])->owner_id) {
            return abort(403);
        }
        return view('pages.client.create-episode', ['action' => 'edit', 'episode' => $episode]);
    }

    public function saveEpisode(Request $request) {
        if (empty($request->get('id'))) {
            $episode = new PodcastEpisode();
            $episode->podcast_id = $request->get('podcast-id');
        } else {
            $episode = PodcastEpisode::find($request->get('id'));
            if (Auth::id() != Podcast::find($episode['podcast_id'])->owner_id) {
                return abort(403);
            }
        }
        $episode->name = $request->get('name');
        $episode->description = $request->get('description');

        $episode->tags = $request->get('tags');
        $episode->status = ($request->get('status') == 1 ? PodcastEpisode::STATUS_PUBLISHED : PodcastEpisode::STATUS_DRAFT);

        if ($request->has('source')) {
            if (isset($episode->source)) {
                File::delete(PodcastEpisode::UPLOADS_AUDIO . '/' . $episode->source);
            }
            $filename = time() . '_' . $request->source->getClientOriginalName();
            $request->source->move(PodcastEpisode::UPLOADS_AUDIO, $filename);
            $episode->source = $filename;
            $episode->filename = $request->source->getClientOriginalName();
        }
        elseif ($request->file_remove){
            $episode->source = '';
            unlink(PodcastEpisode::UPLOADS_AUDIO . '/' . $episode->source);
        }

        $episode->save();

        return \redirect('podcasts/' . $episode->podcast_id . '/view');
    }

    public function deleteEpisode(Request $request) {
        $episode = PodcastEpisode::where('id', '=', $request->get('id'))->first();
        $podcast = Podcast::where('id', '=', $episode->podcast_id)->first()->toArray();
        if (Auth::id() == $podcast['owner_id']) {
            if (!empty($episode->source)) {
                unlink(PodcastEpisode::UPLOADS_AUDIO . '/' . $episode->source);
            }
            DownloadRecord::where('episode_id', '=', $episode->id)->delete();
            QueuedEpisode::where('episode_id', '=', $episode->id)->delete();
            HistoryRecord::where('episode_id', '=', $episode->id)->delete();
            PlaylistRecord::where('episode_id', '=', $episode->id)->delete();
            $episode->delete();
            return $podcast['id'];
        }

        return abort(405);
    }

    public function subscriptions() {
        $podcasts = Podcast::where('status', '=', Podcast::STATUS_ACTIVE)
            ->where('ps.user_id', '=', Auth::id())
            ->join('podcasts_subscriptions AS ps', 'ps.podcast_id', '=', 'podcasts.id')
            ->distinct()->get()->toArray();
        return view('pages.client.subscriptions', ['podcasts' => $podcasts]);
    }

    public function subscribeTo(Request $request) { //podcast_id
        $id = $request->get('id');
        $sub = PodcastSub::where('user_id', '=', Auth::id())->where('podcast_id', '=', $id)->get();
        $podcast = Podcast::where('id', '=', $id)->get()->toArray()[0];
        if (count($sub)) {
            PodcastSub::where('user_id', '=', Auth::id())->where('podcast_id', '=', $id)->delete();
            return view('partials.sub-button', ['podcast' => $podcast])->render();
        } else {
            $sub = new PodcastSub();
            $sub->user_id = Auth::id();
            $sub->podcast_id = $id;
            $sub->save();
            return view('partials.unsub-button', ['podcast' => $podcast])->render();
        }
    }

    public function listenLater() {
        $episodes= PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id', 'users_queues.created_at')
            ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
            ->where('p.status', '=', Podcast::STATUS_ACTIVE)
            ->where('users_queues.user_id', '=', Auth::id())
            ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
            ->join('users_queues', 'users_queues.episode_id', '=', 'podcasts_episodes.id')
            ->orderBy('users_queues.created_at', 'ASC')
            ->distinct()->get()->toArray();
        return view('pages.client.listen-later', ['episodes' => $episodes]);
    }

    public function queueToListenLater(Request $request) {
        $user = Auth::user();
        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);

        $id = $request->get('id');
        $mark = QueuedEpisode::where('user_id', '=', $user->id)->where('episode_id', '=', $id)->get();
        if (count($mark)) {
            QueuedEpisode::where('user_id', '=', $user->id)->where('episode_id', '=', $id)->delete();

        } else {
            $mark = new QueuedEpisode();
            $mark->user_id = $user->id;
            $mark->episode_id = $id;
            $mark->save();
        }
        $user->refresh();

        $listenLater = $user->listenLater;
        $episodes = $listenLater->pluck('episode_id')->toArray();
        return response()->json([
            'status' => 'success',
            'episodes' => $episodes
        ]);
    }

    public function saveWatchTime(Request $request) {
        $user = Auth::user();
        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'Auth required']);

        $episodeId = $request->get('episode_id');
        $episode = PodcastEpisode::find($episodeId);
        if (!$episode)
            return response()->json(['status' => 'error', 'message' => 'No episode found']);
        $time = $request->get('time');
        $model = HistoryRecord::where('user_id', '=', $user->id)->where('episode_id', '=', $episodeId)->first();
        if ($model) {
            $model->time = $time;
            $model->save();
        } else {
            $model = new HistoryRecord();
            $model->user_id = $user->id;
            $model->episode_id = $episodeId;
            $model->time = $time;
            $model->save();
        }
        //$user->refresh();


        return response()->json([
            'status' => 'success',
            'episode_id' => $episodeId,
            'is_listened' => $model->is_listened,
            'duration_left_label' => $episode->duration_left_label
        ]);
    }

    public function setListened(Request $request) {
        $user = Auth::user();
        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'Auth required']);

        $episodeId = $request->get('id');
        $model = HistoryRecord::where('user_id', '=', $user->id)->where('episode_id', '=', $episodeId)->first();
        if (!$model)
            $model = new HistoryRecord();

        $model->is_listened = 1;
        $model->save();

        //$user->refresh();


        return response()->json([
            'status' => 'success',
            'episode_id' => $episodeId,
            'is_listened' => $model->is_listened
        ]);
    }

    public function addToPlaylist($episode_id){
        $user = Auth::user();
        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);

        $episode = PodcastEpisode::find($episode_id);
        if (!$episode) {
            return response()->json(['status' => 'error', 'message' => 'Episode not found'], 404);
        }

        // Check if the playlist item already exists
        $existingPlaylistItem = $user->playlist->where('episode_id', $episode->id)->first();

        if ($existingPlaylistItem) {
            // If the playlist item exists, remove it
            $existingPlaylistItem->delete();
        } else {
            // If the playlist item does not exist, create a new playlist item
            $newPlaylistItem = new PlaylistRecord();
            $newPlaylistItem->user_id = $user->id;
            $newPlaylistItem->episode_id = $episode->id;
            $newPlaylistItem->sort = 0;
            $newPlaylistItem->save();
        }

        $user->refresh();
        $playlist = $user->playlist;
        $episodes = $playlist->pluck('episode_id')->toArray();
        return response()->json([
            'status' => 'success',
            'html' => view('partials.player-playlist', ['list' => $playlist])->render(),
            'episodes' => $episodes
        ]);

    }
    public function savePlaylistSorting(Request $request){
        $user = Auth::user();
        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        foreach ($request['playlist'] as $key => $episodeId){
            PlaylistRecord::where([
                ['user_id', '=', $user->id],
                ['episode_id', '=', $episodeId]
            ])->update(['sort' => $key]);
        }

        return response()->json([
            'status' => 'success',
        ]);

    }

    public function history() { //episodes only
        if (Auth::check()) {
            $history = PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
                ->where('uh.user_id', '=', Auth::id())
                ->where('podcasts_episodes.status', '=', PodcastEpisode::STATUS_PUBLISHED)
                ->where('p.status', '=', Podcast::STATUS_ACTIVE)
                ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
                ->join('users_history AS uh', 'uh.episode_id', '=', 'podcasts_episodes.id')
                ->distinct()->get()->toArray();
        } else {
            $history = [];
        }
        return view('pages.client.history', ['episodes' => $history]);
    }

    public function recordListeningHistory(Request $request) {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Auth required']);
        }

        $episode_id = $request->get('id');
        HistoryRecord::updateOrCreate(['episode_id' => $episode_id, 'user_id' => Auth::id()], ['is_listened' => 1]);

        return response()->json([
            'status' => 'success',
            'episode_id' => $episode_id,
            'is_listened' => 1
        ]);
    }

    public function clearHistory() {
        HistoryRecord::where('user_id', '=', Auth::id())->delete();
        return '';
    }

    public function downloads(Request $request) {
        if (Auth::check()) {
            $downloads =
            PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
                ->where('ud.user_id', '=', Auth::id())
                ->join('podcasts AS p', 'podcasts_episodes.podcast_id', '=', 'p.id')
                ->join('users_downloads AS ud', 'ud.episode_id', '=', 'podcasts_episodes.id')
                ->distinct()->get()->toArray();
        } else {
            $downloads = [];
        }
        return view('pages.client.downloads', ['episodes' => $downloads]);
    }

    public function settings() {
        return view('pages.client.profile-settings');
    }

    public function changeName(ChangeNameRequest $request)
    {
        $data = $request->validated();
        try {
            $this->userService->changeName($request->user(), $data['name']);
        } catch (Throwable $e) {
            return response()->json(
                ['status' => 'error', 'message' => 'Серверная ошибка. Обратитесь к администратору.'],
                500);
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'redirectUrl' => route('profile.settings')
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        try {
            $this->userService->changePassword($request->user(), $data['password_new']);
        } catch (Throwable $e) {
            return response()->json(
                ['status' => 'error', 'message' => 'Серверная ошибка. Обратитесь к администратору.'],
                500);
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'redirectUrl' => route('profile.settings')
        ]);
    }

    public function changeLanguage(Request $request) {
        $lang = strtolower($request->get('lang'));
        if (in_array($lang, ['en', 'ro', 'ru']) ) {
            $user = Auth::user();
            $user->language = $lang;
            $user->save();
            app()->setLocale($lang);
            session()->put('locale', $lang);
            //view()->share(['lang' => $lang]);
            return $lang;
        } else {
            return '';
        }
    }

    public function logout() {
        Auth::logout();
    }
}
