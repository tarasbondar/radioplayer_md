<?php

namespace App\Http\Controllers;

use App\Models\RadioStationGroup;
use Illuminate\Http\Request;

class RadioStationGroupController extends Controller
{
    public function index(Request $request) {
        $page_size = $request->get('page-size',10);
        $page = $request->get('page', 1);

        $groups = RadioStationGroup::select("*")
            ->offset(($page - 1) * $page_size)->limit($page_size);

        $pagination = $groups->paginate($page_size)->withQueryString()->links();
        $groups = $groups->get()->toArray();

        return view('pages.admin.stationgroups', ['groups' => $groups, 'pagination' => $pagination]);
    }

    public function add() {
        return view('pages.admin.stationgroups-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $group = RadioStationGroup::find($id)->toArray();
        return view('pages.admin.stationgroups-edit', ['action' => 'edit', 'group' => $group]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $group = new RadioStationGroup();
        } else {
            $group = RadioStationGroup::find($request['id']);
        }
        $group->key = $request['key'];
        $group->save();

        return redirect()->action([RadioStationGroupController::class, 'index']);
    }

    public function delete($id) {
        $group = RadioStationGroup::find($id);
        //all affected stations group_id = 0
        $group->delete();
        return '';
    }
}
