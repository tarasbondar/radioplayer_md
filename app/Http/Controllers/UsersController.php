<?php

namespace App\Http\Controllers;

use App\Models\AuthorApplication;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $role = $request->get('status', 1);

        $users = User::select("*")/*->where('role', '=', $status)*/;
        $pagination = User::paginate($page_size)->links();

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
        // stats
        return view('pages.admin.users-view', ['user' => $user, 'users_podcast' => $users_podcasts]);
    }

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

    public function delete($id) {
        //owned podcasts if author
        //queues
        //history
        //downloads
    }

    public function authorApps(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $status = $request->get('status', 1);

        $apps = AuthorApplication::select('*')->where('status', '=', $status);
        $pagination = $apps->paginate($page_size)->appends(['status' => $status])->links(); //appends($request->all())

        $apps = $apps->orderBy('created_at', 'asc')->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();
        return view('pages.admin.authorapplications', ['status' => $status, 'apps' => $apps, 'pagination' => $pagination]);
    }

    public function authorAppsEdit($id) {
        $app = AuthorApplication::find($id)->toArray();
        $user = User::find($app['user_id']);
        $categories_keys = PodcastCategory::select('key')->whereIn('id', explode(', ', $app['categories']))->where('status', '=', PodcastCategory::STATUS_ACTIVE)->get()->toArray();
        $categories = [];
        foreach ($categories_keys as $k) {array_push($categories, $k['key']);}
        return view('pages.admin.authorapplications-review', ['app' => $app, 'user' => $user, 'categories' => implode(', ', $categories)]);
    }

    public function reviewApp(Request $request) {
        if ($request->get('status') == AuthorApplication::STATUS_APPROVED) {
            $app = AuthorApplication::find($request->get('id'));
            $app->status = AuthorApplication::STATUS_APPROVED;
            $app->save();
            $user = User::find($app->user_id);
            $user->role = User::ROLE_AUTHOR;
            $user->save();
        } elseif ($request->get('status') == AuthorApplication::STATUS_DECLINED) {
            $app = AuthorApplication::find($request->get('id'));
            $app->status = AuthorApplication::STATUS_DECLINED;
            $app->feedback_message = $request->get('feedback');
            $app->save();
            //notification?
        }

        return;
        //return $request->all();
    }

}
