<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use Illuminate\Support\Facades\Auth;

class PodcastEpisodeController extends Controller
{
    public function index(Request $request) {
        $page_size = ($request->has('page-size') ? $request->get('page-size') : 10);
        $page = ($request->has('page') ? $request->get('page') : 1);
        $episodes = PodcastEpisode::select("podcasts_episodes.*", "podcasts.name AS podcast_name")->join('podcasts', 'podcasts.id', '=', 'podcasts_episodes.podcast_id');

        $pagination = $episodes->paginate($page_size)->links();

        $episodes = $episodes->offset(($page - 1) * $page_size)->limit($page_size)
            ->get()->toArray();

        return view('pages.admin.podcastsepisodes', ['episodes' => $episodes, 'pagination' => $pagination]);
    }

    public function add() {
        $podcasts = $this->getPodcastsByOwner(Auth::id());
        return view('pages.admin.podcastsepisodes-edit', ['action' => 'add', 'podcasts' => $podcasts]);
    }

    public function edit($id) {
        $episode = PodcastEpisode::find($id)->toArray();
        $podcast = Podcast::find($episode['podcast_id'])->toArray();

        if ($podcast['owner_id'] == Auth::id()) {
            $podcasts = Podcast::where('owner_id', '=', Auth::id())->get()->toArray();
            return view('pages.admin.podcastsepisodes-edit', ['action' => 'edit', 'episode' => $episode, 'podcasts' => $podcasts]);
        }

        return view('pages.admin.podcastsepisodes-edit', ['action' => 'edit', 'episode' => $episode, 'podcast' => $podcast]);
    }

    public function save(Request $request) {
        if (empty($request['id'])) {
            $episode = new PodcastEpisode();
        } else {
            $episode= PodcastEpisode::find($request['id']);
        }
        $episode->podcast_id = $request->get('podcast');
        $episode->name = $request->get('name');
        $episode->description = $request->get('description');
        $episode->status = $request->get('tags');
        $episode->status = $request->get('status');
        $episode->save();

        return redirect()->action([PodcastEpisodeController::class, 'index']);
    }

    public function delete($id) {
        $episode = PodcastEpisode::find($id);
        $episode->delete();
        //file
        return '';
    }

    public function getPodcastsByOwner($user_id) {
        $podcasts = Podcast::where('owner_id', '=', $user_id)->get()->toArray();
        return $podcasts;
    }

    public function getEpisodesByPodcast($podcast_id) {
        $episodes = PodcastEpisode::where('podcast_id', '=', $podcast_id)->get()->toArray();
        return $episodes;
    }

}
