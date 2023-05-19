<?php

namespace App\Http\Controllers;

use App\Models\RadioStation2Category;
use Illuminate\Http\Request;

use App\Models\RadioStationCategory;

class RadioStationCategoryController extends Controller
{

    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $categories = RadioStationCategory::select("*")
            ->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();
        $pagination = RadioStationCategory::paginate($page_size)->links();
        return view('pages.admin.stationcategories', ['categories' => $categories, 'pagination' => $pagination]);
    }

    public function add() {
        return view('pages.admin.stationcategories-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $category = RadioStationCategory::find($id)->toArray();
        return view('pages.admin.stationcategories-edit', ['action' => 'edit', 'category' => $category]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $category = new RadioStationCategory();
        } else {
            $category = RadioStationCategory::find($request['id']);
        }
        $category->key = $request['key'];
        $category->status = $request['status'];
        $category->save();

        return redirect()->action([RadioStationCategoryController::class, 'index']);
    }

    public function delete($id) {
        $category = RadioStationCategory::find($id);
        RadioStation2Category::whereRaw('category_id = ' . $id)->delete();
        $category->delete();
        return '';
    }
}
