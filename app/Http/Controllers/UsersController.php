<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\AuthorApplication;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\PlaylistRecord;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\PodcastEpisode;
use App\Models\QueuedEpisode;
use App\Models\RadioStationFavorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $username = $request->get('username', '');
        $email = $request->get('email', '');
        //$role = $request->get('status', 1); //->where('role', '=', $status)
        $registered_from = $request->get('registered-from', '');
        $registered_to = $request->get('registered-to', '');
        $logged_from = $request->get('logged-from', '');
        $logged_to = $request->get('logged-to', '');
        $appends = [];

        $users = User::select("*");
        if (!empty($username)) {
            $users = $users->where('name', 'LIKE', "%{$username}%");
            $appends['username'] = $username;
        }

        if (!empty($email)) {
            $users = $users->where('email', 'LIKE', "%{$email}%");
            $appends['email'] = $email;
        }

        if (!empty($registered_from)) {
            $users = $users->where('created_at', '>=', $registered_from . ' 00:00:00');
            $appends['registered-from'] = $registered_from;
        }

        if (!empty($registered_to)) {
            $users = $users->where('created_at', '<=', $registered_to . ' 23:59:59');
            $appends['registered-to'] = $registered_to;
        }

        if (!empty($logged_from)) {
            $users = $users->where('last_login_at', '>=', $logged_from . ' 00:00:00');
            $appends['logged-from'] = $logged_from;
        }

        if (!empty($logged_to)) {
            $users = $users->where('last_login_at', '<=', $logged_to . ' 23:59:59');
            $appends['logged-to'] = $registered_to;
        }

        if ($page > 1) {
            $appends['page'] = $page;
        }

        $pagination = $users->paginate($page_size)->appends($appends)->links();

        $users = $users
            ->orderBy('created_at', 'desc')->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();

        return view('pages.admin.users', ['users' => $users, 'pagination' => $pagination]);
    }

    public function changeStatus(Request $request) {
        $user = User::find($request->get('id'));
        if ($user->status == User::STATUS_NORMAL) {
            $user->status = User::STATUS_BLOCKED;
            $msg = 'Unblock';
        } else {
            $user->status = User::STATUS_NORMAL;
            $msg = 'Block';
        }
        $user->save();
        return $msg;
    }

    public function view($id) {
        $user = User::find($id)->toArray();

        $users_podcasts = [];
        if ($user['role'] == User::ROLE_AUTHOR) {
            $users_podcasts = Podcast::where('owner_id', '=', $id)->get()->toArray();
        }

        $user_stats = [];
        $user_stats['episodes_listened'] = HistoryRecord::where('user_id', '=', $id)->count();
        $user_stats['episodes_downloaded'] = DownloadRecord::where('user_id', '=', $id)->count();
        $user_stats['stations_favorited'] = RadioStationFavorite::where('user_id', '=', $id)->count();

        if ($user['role'] == User::ROLE_AUTHOR) {
            $user_stats['podcasts_created'] = Podcast::where('owner_id', '=', $id)->count();
            $user_stats['episodes_uploaded'] = PodcastEpisode::join('podcasts', 'podcasts.id', '=', 'podcasts_episodes.podcast_id')->where('podcasts.owner_id', '=', $id)->count();
        }


        return view('pages.admin.users-view', ['user' => $user, 'users_podcast' => $users_podcasts, 'stats' => $user_stats]);
    }

    /*public function stats() {

    }*/

    public function add() {
        return view('pages.admin.users-edit', ['action' => 'add', 'model' => [], 'roles' => User::$roles, 'statuses' => User::$statuses]);
    }

    public function edit($id) {
        $model = User::find($id)->toArray();
        return view('pages.admin.users-edit', ['action' => 'edit', 'model' => $model, 'roles' => User::$roles, 'statuses' => User::$statuses]);
    }

    public function save(Request $request) {
        $rules = [
            'name' => 'required',
            'email' => ['required', 'max:255', Rule::unique('users')->ignore($request['id'])],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (empty($request['id'])) {
            $model = new User();
            $action = 'add';
        } else {
            $model = User::find($request['id']);
            $action = 'edit';
        }

        if ($validator->fails()) {
            return redirect('/admin/users/'. $action .'/'.$request['id'])
                ->withErrors($validator)
                ->withInput();
        }


        $model->name = $request['name'];
        $model->email = $request['email'];
        $model->role = $request['role'];
        $model->status = $request['status'];
        $model->language = 'en';
        if ($request['password'] != '')
            $model->password = Hash::make($request['password']);
        $model->save();

        return redirect()->action([UsersController::class, 'index']);
    }

    public function download(Request $request) {
        $args = [
            'username' => $request->get('username', ''),
            'email' => $request->get('email', ''),
            'registered_from' => $request->get('registered-from', ''),
            'registered_to' => $request->get('registered-to', ''),
            'logged_from' => $request->get('logged-from', ''),
            'logged_to' => $request->get('logged-to', ''),
        ];
        return Excel::download(new UsersExport($args), '1.xlsx');
    }

    public function delete($id) {
        $user = User::where('id', '=', $id)->first();
        if ($user->role > 0) {
            $podcasts = Podcast::where('owner_id', '=', $user->id)->get();
            if ($podcasts->count()) {
                foreach($podcasts as $podcast) {
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
                }
            }
        }
        DownloadRecord::where('user_id', '=', $user->id)->delete();
        QueuedEpisode::where('user_id', '=', $user->id)->delete();
        HistoryRecord::where('user_id', '=', $user->id)->delete();

        $user->delete();
        return '';

    }

    public function authorApps(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $status = $request->get('status', 'all');
        $username = $request->get('username', '');
        $email = $request->get('email', '');
        $appends['status'] = $status;
        //registered and login

        $apps = AuthorApplication::select('author_applications.*', "users.name AS username", "users.email AS email")
            ->join('users', 'users.id', '=', 'author_applications.user_id');

        if ($status != 'all') {
            $apps = $apps->where('author_applications.status', '=', $status);
        }

        if (!empty($username)) {
            $apps = $apps->where('users.name', 'LIKE', "%{$username}%");
            $appends['username'] = $username;
        }

        if (!empty($email)) {
            $apps = $apps->where('users.email', 'LIKE', "%{$email}%");
            $appends['email'] = $email;
        }

        if ($page > 1) {
            $appends['page'] = $page;
        }

        $pagination = $apps->paginate($page_size)->appends($appends)->links(); //appends($request->all())

        $apps = $apps->orderBy('created_at', 'desc')->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();
        return view('pages.admin.authorapplications', ['status' => $status, 'apps' => $apps, 'pagination' => $pagination, 'appends' => $appends]);
    }

    public function authorAppsEdit($id) {
        $app = AuthorApplication::find($id)->toArray();
        $user = User::find($app['user_id']);
        $categories_keys = PodcastCategory::whereIn('id', explode(',', $app['categories']))->where('status', '=', PodcastCategory::STATUS_ACTIVE)->get();
        $categories = [];
        foreach ($categories_keys as $k) {array_push($categories, $k->getTranslation('title'));}
        return view('pages.admin.authorapplications-review', ['app' => $app, 'user' => $user, 'categories' => implode(', ', $categories)]);
    }

    public function reviewApp(Request $request) {
        $status = $request->get('status');
        $app = AuthorApplication::find($request->get('id'));
        $user = User::find($app->user_id);
        if ($status == AuthorApplication::STATUS_APPROVED) {
            $user->role = User::ROLE_AUTHOR;
        } elseif ($status == AuthorApplication::STATUS_DECLINED || $status == AuthorApplication::STATUS_NO_RETRY) {
            $app->feedback_message = $request->get('feedback');
            $user->role = User::ROLE_USER;
        }

        $user->save();
        $app->status = $status;
        $app->save();

        return '';
    }

}
