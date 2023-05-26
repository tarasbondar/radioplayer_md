<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\AuthorApplication;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\Playlist;
use App\Models\Podcast;
use App\Models\Podcast2Category;
use App\Models\PodcastCategory;
use App\Models\PodcastEpisode;
use App\Models\PodcastSub;
use App\Models\QueuedEpisode;
use App\Models\RadioStation;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Throwable;

//use Illuminate\Support\Facades\Redirect;
//use function Symfony\Component\ErrorHandler\reRegister;

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
            $app = AuthorApplication::where('user_id', '=', Auth::id())->orderBy('updated_at', 'desc')->first();

            if (!empty($app)) {
                if ($app->status == AuthorApplication::STATUS_PENDING) {
                    return view('pages.client.author-application', ['status' => 'pending',]);
                }
                if ($app->status == AuthorApplication::STATUS_DECLINED) {
                    /*$day_ago = date('Y-m-d H:i:s', strtotime("-1 day"));
                    if ($app->updated_at > $day_ago) {
                        //display form
                    } else {
                        //display wait
                    }*/
                    return view('pages.client.author-application', ['status' => 'declined', 'feedback' => $app->feedback_message]);
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
        //file
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

        return $this->myPodcasts();
    }

    public function deletePodcast() {

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

    public function favStation($id) {
        if (!Auth::check()) {
            return 'no auth';
        }

        $exists = DB::table('radiostations_favorites')->select('*')
            ->where('station_id', '=', $id)
            ->where('user_id', '=', Auth::id())
            ->get()->count();

        if ($exists) {
            DB::table('radiostations_favorites')->where(['station_id' => $id, 'user_id' => Auth::id()])->delete();
            return ['action' => 'deleted', 'id' => $id];
        } else {
            DB::table('radiostations_favorites')->insert(['station_id' => $id, 'user_id' => Auth::id()]);
            $station = RadioStation::find($id)->toArray();
            $station['favorited'] = 1;
            $output = view('partials.station-card', ['station' => $station])->render();
            return ['action' => 'added', 'output' => $output, 'id' => $id];
        }
    }

    public function createEpisode($podcast_id) {
        $podcast = Podcast::find($podcast_id);
        if (Auth::user()->role < User::ROLE_AUTHOR || Auth::id() != $podcast->owner_id) {
            return abort(403);
        }
        return view('pages.client.create-episode', ['action' => 'add', 'podcast_id' => $podcast_id]);
    }

    public function editEpisode($id) {
        $episode = PodcastEpisode::find($id)->toArray();
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
        $episode->source = '';//file
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

        $episode->save();

        return \redirect('podcasts/' . $episode->podcast_id . '/view');
    }

    public function deleteEpisode($id) {
        $episode = PodcastEpisode::where('id', '=', $id)->get();
        $podcast = Podcast::where('id', '=', $episode['id'])->get()->toArray()[0];
        if (Auth::id() == $podcast['owner_id']) {
            //file
            $episode->delete();
            return '';
        }

        return abort(405);
    }

    public function subscriptions() {
        /*$subs = DB::table('podcasts_subscriptions')
            ->where('user_id', '=', Auth::id())
            ->orderBy('created_at', 'ASC')
            ->get()->toArray();*/
        $podcasts = Podcast::where('status', '=', Podcast::STATUS_ACTIVE)
            ->where('ps.user_id', '=', Auth::id())
            ->join('podcasts_subscriptions AS ps', 'ps.podcast_id', '=', 'podcasts.id')
            ->get()->toArray();
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
        $episodes= PodcastEpisode::select('podcasts_episodes.*', 'p.name AS podcast_name', 'p.owner_id AS user_id')
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
        $id = $request->get('id');
        $mark = QueuedEpisode::where('user_id', '=', Auth::id())->where('podcast_id', '=', $id)->get();
        if (count($mark)) {
            QueuedEpisode::where('user_id', '=', Auth::id())->where('podcast_id', '=', $id)->delete();
            return 'removed';
        } else {
            $mark = new QueuedEpisode();
            $mark->user_id = Auth::id();
            $mark->podcast_id = $id;
            $mark->save();
            return 'queued';
        }
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
            $newPlaylistItem = new Playlist();
            $newPlaylistItem->user_id = $user->id;
            $newPlaylistItem->episode_id = $episode->id;
            $newPlaylistItem->sort = 0;
            $newPlaylistItem->save();
        }

        $user->refresh();
        return response()->json([
            'status' => 'success',
            'html' => view('partials.player-playlist', ['list' => $user->playlist])->render()
        ]);

    }

    public function history() { //episodes only
        $history = HistoryRecord::where('user_id', '=', Auth::id())
            ->get()->toArray();
        return view('pages.client.history', ['history' => $history]);
    }

    public function recordListeningHistory(Request $request) {
        $episode_id = $request->get('id');
        HistoryRecord::upsert(['episode_id' => $episode_id, 'user_id' => Auth::id()], ['created_at' => date('Y-m-d H:i:s')]);
        return '';
    }

    public function clearHistory() {
        HistoryRecord::where('user_id', '=', Auth::id())->delete();
        return '';
    }

    public function downloads(Request $request) { //episodes users_downloads
        $downloads = DownloadRecord::where(['user_id' => Auth::id()])->get()->toArray();
        return $downloads;
    }

    public function downloadEpisode(Request $request) {
        $id = $request->get('id');
        var_dump($id); exit;
        $episode = PodcastEpisode::where('id', '=', $id)->get()->toArray()[0];
        if ($episode['status'] == PodcastEpisode::STATUS_PUBLISHED) {
            //download file
            //if ok
            $record = new DownloadRecord();
            $record->user_id = Auth::id();
            $record->episode_id = $episode['id'];
            $record->save();
            return '';
            /*
            $myFile = storage_path("folder/dummy_pdf.pdf");
    	    return response()->download($myFile);
             * */
        }

        return 'error';
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
