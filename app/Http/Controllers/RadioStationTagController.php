<?php

namespace App\Http\Controllers;

use App\Models\RadioStationTag;
use App\Models\RadioStation2Tag;
use Illuminate\Http\Request;

class RadioStationTagController extends Controller
{
    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $tags = RadioStationTag::select("*")
            ->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();
        $pagination = RadioStationTag::paginate($page_size)->links();
        return view('pages.admin.stationtags', ['tags' => $tags, 'pagination' => $pagination]);
    }

    public function add() {
        return view('pages.admin.stationtags-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $tag = RadioStationTag::find($id)->toArray();
        return view('pages.admin.stationtags-edit', ['action' => 'edit', 'tag' => $tag]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $tag = new RadioStationTag();
        } else {
            $tag = RadioStationTag::find($request['id']);
        }
        $tag->key = $request['key'];
        $tag->status = $request['status'];
        $tag->save();

        return redirect()->action([RadioStationTagController::class, 'index']);
    }

    public function delete($id) {
        $tag = RadioStationTag::find($id);
        RadioStation2Tag::whereRaw('tag_id = ' . $id)->delete();
        $tag->delete();
        return '';
    }
}
