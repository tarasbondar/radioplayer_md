<?php

namespace App\Http\Controllers;

use App\Models\PodcastCategory;
use App\Models\Podcast2Category;
use Illuminate\Http\Request;

class PodcastCategoryController extends Controller
{

    public function index(Request $request) {
        $categories = PodcastCategory::all()->toArray();
        return view('pages.admin.podcastcategories', ['categories' => $categories]);
    }

    public function add() {
        return view('pages.admin.podcastcategories-edit', ['action' => 'add']);
    }

    public function edit($id) {
        $category = PodcastCategory::find($id)->toArray();
        return view('pages.admin.podcastcategories-edit', ['action' => 'edit', 'category' => $category]);
    }

    public function save(Request $request) {
        if (empty($request->get('id'))) {
            $category = new PodcastCategory();
        } else {
            $category = PodcastCategory::find($request->get('id'));
        }
        $category->key = $request->get('key');
        $category->status = $request->get('status');
        $category->save();

        return redirect()->action([PodcastCategoryController::class, 'index']);
    }

    public function delete($id) {
        $category = PodcastCategory::find($id);
        Podcast2Category::whereRaw('category_id = ' . $id)->delete();
        $category->delete();
        return '';
    }

}
