<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Models\DownloadRecord;
use App\Models\HistoryRecord;
use App\Models\Podcast;
use App\Models\QueuedEpisode;
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
        $episode = PodcastEpisode::find($id);
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
                File::delete(PodcastEpisode::UPLOADS_AUDIO . '/' . $episode->source);
            }
            $filename = time() . '_' . $request->source->getClientOriginalName();
            $request->source->move(PodcastEpisode::UPLOADS_AUDIO, $filename);
            $episode->source = $filename;
            $episode->filename = $request->source->getClientOriginalName();
        }

        $episode->save();

        // save translations
        $languages = LanguageHelper::getLanguages();
        foreach ($episode->getTranslatableAttributes() as $attribute) {
            foreach ($languages as $language) {
                $translationKey = $attribute.'_'.$language->code;
                $translationValue = $request->input($translationKey);

                if (!empty($translationValue)) {
                    $episode->setTranslation($attribute, $language->code, $translationValue);
                }
            }
        }

        return redirect()->action([PodcastEpisodeController::class, 'index']);
    }

    public function delete($id) {
        $episode = PodcastEpisode::where('id', '=', $id)->first();
        unlink(PodcastEpisode::UPLOADS_AUDIO . '/' . $episode->source);
        DownloadRecord::where('episode_id', '=', $episode->id)->delete();
        QueuedEpisode::where('episode_id', '=', $episode->id)->delete();
        HistoryRecord::where('episode_id', '=', $episode->id)->delete();
        $episode->delete();

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
