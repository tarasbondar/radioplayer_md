<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

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
        $episode->tags = $request->get('tags');
        $episode->status = $request->get('status');

        if ($request->has('source')) {
            if (isset($episode->source)) {
                File::delete(Podcast::UPLOADS_IMAGES . '/' . $episode->source);
            }
            $filename = time() . '_' . $request->source->getClientOriginalName();
            $request->source->move(PodcastEpisode::UPLOADS_AUDIO, $filename);
            $episode->source = $filename;
        }

        $episode->save();

        return redirect()->action([PodcastEpisodeController::class, 'index']);
    }

    public function uploadAudio(Request $request) {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            return [
                'status' => false
            ];
        }

        $upload = $receiver->receive();
        if ($upload->isFinished()) {
            $file = $upload->getFile();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . str_replace('.' . $extension, '', $file->getClientOriginalName()) . '.' . $extension;
            $path = public_path(PodcastEpisode::UPLOADS_AUDIO);
            $file->move($path, $filename);

            return [
                'path' => $path,
                'filename' => $filename
            ];
        }

        $handler = $upload->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
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
