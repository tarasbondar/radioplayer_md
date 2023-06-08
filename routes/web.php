<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\RadioStationController;
use App\Http\Controllers\PodcastCategoryController;
use App\Http\Controllers\PodcastEpisodeController;
use App\Http\Controllers\RadioStationCategoryController;
use App\Http\Controllers\RadioStationTagController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadioStationGroupController;
use App\Http\Middleware\IsAdmin;
use \Illuminate\Support\Facades\Auth;

require __DIR__.'/auth.php';

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    if (Auth::check()){
        $user = Auth::user();
        $user->language = $locale;
        $user->save();
    }

    if (url()->previous() !== url()->current()) {
        return redirect()->back();
    }
    return redirect('/');
});

Route::get('/', [IndexController::class, 'index']);
Route::get('/home', [IndexController::class, 'index']);
Route::get('/podcasts', [IndexController::class, 'podcasts']);
Route::get('/favorite-station/{id}', [IndexController::class, 'favStation']);
Route::post('/update-stations', [IndexController::class, 'updateStations']);
Route::get('/play-station/{id}', [IndexController::class, 'playStation']);
Route::get('/get-station-info/{id}', [IndexController::class, 'getStationInfo']);
Route::get('/apply', [ProfileController::class, 'apply']);
Route::post('/send-application', [ProfileController::class, 'sendApplication']);
Route::get('/privacy', [IndexController::class, 'privacy']);

Route::post('/add-to-playlist/{id}', [ProfileController::class, 'addToPlaylist'])->name('profile.addToPlaylist');
Route::post('/save-playlist-sorting', [ProfileController::class, 'savePlaylistSorting'])->name('profile.savePlaylistSorting');

Route::get('/my-podcasts', [ProfileController::class, 'myPodcasts']);
Route::post('/my-podcasts-search', [ProfileController::class, 'myPodcastsSearch']);
Route::get('/all-podcasts', [IndexController::class, 'allPodcasts'])->name('index.allPodcasts');
Route::post('/all-search', [IndexController::class, 'allSearch']);
Route::post('/append-episodes', [IndexController::class, 'appendEpisodes']);
Route::get('/podcasts', [IndexController::class, 'podcasts']);
Route::post('/update-podcasts', [IndexController::class, 'updatePodcasts']);
Route::get('/podcasts/{id}/view', [IndexController::class, 'viewPodcast']);
Route::get('/author/{id}', [IndexController::class, 'viewByAuthor']);
Route::get('/create-podcast', [ProfileController::class, 'createPodcast']);
Route::get('/edit-podcast/{id}', [ProfileController::class, 'editPodcast']);
Route::post('/save-podcast', [ProfileController::class, 'savePodcast']);
Route::delete('/delete-podcast', [ProfileController::class, 'deletePodcast']);

Route::get('/episodes/{id}/view', [IndexController::class, 'viewEpisode']);
Route::get('/create-episode/{id}', [ProfileController::class, 'createEpisode']);
Route::get('/edit-episode/{id}', [ProfileController::class, 'editEpisode']);
Route::get('/download-episode', [IndexController::class, 'downloadEpisode']);
Route::post('/save-episode', [ProfileController::class, 'saveEpisode']);
Route::post('/get-download-data', [IndexController::class, 'getDownloadData']);
Route::delete('/delete-episode', [ProfileController::class, 'deleteEpisode']);

Route::get('/subscriptions', [ProfileController::class, 'subscriptions']);
Route::post('/subscribe-to', [ProfileController::class, 'subscribeTo']);
Route::get('/listen-later', [ProfileController::class, 'listenLater']);
Route::post('/queue-episode', [ProfileController::class, 'queueToListenLater']);
Route::post('/save-watch-time', [ProfileController::class, 'saveWatchTime'])->name('profile.saveWatchTime');
Route::get('/history', [ProfileController::class, 'history']);
Route::post('/record-history', [ProfileController::class, 'recordListeningHistory']);
//Route::post('/set-listened', [ProfileController::class, 'setListened'])->name('profile.setListened');
Route::post('/clear-history', [ProfileController::class, 'clearHistory']);
Route::get('/downloads', [ProfileController::class, 'downloads']);
Route::get('/play-episode/{id}', [IndexController::class, 'playEpisode']);

Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
Route::post('/settings/change-name', [ProfileController::class, 'changeName'])->name('profile.changeName');
Route::post('/settings/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
Route::post('/settings/change-language', [ProfileController::class, 'changeLanguage'])->name('profile.changeLanguage');


Route::get('/privacy-policy', [IndexController::class, 'privacy']);

Route::name('admin.')->prefix('admin')->middleware([IsAdmin::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users/status', [UsersController::class, 'changeStatus']);
    Route::get('/users/add', [UsersController::class, 'add']);
    Route::get('/users/view/{id}', [UsersController::class, 'view']);
    Route::get('/users/edit/{id}', [UsersController::class, 'edit']);
    Route::post('/users/save', [UsersController::class, 'save']);
    Route::delete('/users/{id}', [UsersController::class, 'delete']);
    Route::get('/users/download', [UsersController::class, 'download']);

    Route::get('/author-apps/{status?}', [UsersController::class, 'authorApps']);
    Route::get('/author-apps-review/{id}', [UsersController::class, 'authorAppsEdit']);
    Route::post('/author-apps-review', [UsersController::class, 'reviewApp']);

    Route::get('/podcasts', [PodcastController::class, 'index']);
    Route::get('/podcasts/add', [PodcastController::class, 'add']);
    Route::get('/podcasts/edit/{id}', [PodcastController::class, 'edit']);
    Route::get('/podcasts/stats', [PodcastController::class, 'stats']);
    Route::post('/podcasts/save', [PodcastController::class, 'save']);
    Route::delete('/podcasts/{id}', [PodcastController::class, 'delete']);
    Route::get('/podcasts/download', [PodcastController::class, 'download']);

    Route::get('/podcast-categories', [PodcastCategoryController::class, 'index']);
    Route::get('/podcast-categories/add', [PodcastCategoryController::class, 'add']);
    Route::get('/podcast-categories/edit/{id}', [PodcastCategoryController::class, 'edit']);
    Route::post('/podcast-categories/save', [PodcastCategoryController::class, 'save']);
    Route::delete('/podcast-categories/{id}', [PodcastCategoryController::class, 'delete']);

    Route::get('/podcasts-episodes', [PodcastEpisodeController::class, 'index']);
    Route::get('/podcasts-episodes/add', [PodcastEpisodeController::class, 'add']);
    Route::get('/podcasts-episodes/edit/{id}', [PodcastEpisodeController::class, 'edit']);
    Route::post('/podcasts-episodes/save', [PodcastEpisodeController::class, 'save']);
    Route::post('/podcasts-episodes/upload-audio', [PodcastEpisodeController::class, 'uploadAudio']);
    Route::delete('/podcasts-episodes/{id}', [PodcastEpisodeController::class, 'delete']);

    Route::get('/stations', [RadioStationController::class, 'index']);
    Route::get('/stations/add', [RadioStationController::class, 'add']);
    Route::get('/stations/edit/{id}', [RadioStationController::class, 'edit']);
    Route::post('/stations/save', [RadioStationController::class, 'save']);
    Route::delete('/stations/{id}', [RadioStationController::class, 'delete']);
    Route::get('/stations/download', [RadioStationController::class, 'download']);

    Route::get('/station-categories', [RadioStationCategoryController::class, 'index']);
    Route::get('/station-categories/add', [RadioStationCategoryController::class, 'add']);
    Route::get('/station-categories/edit/{id}', [RadioStationCategoryController::class, 'edit']);
    Route::post('/station-categories/save', [RadioStationCategoryController::class, 'save']);
    Route::delete('/station-categories/{id}', [RadioStationCategoryController::class, 'delete']);

    Route::get('/station-tags', [RadioStationTagController::class, 'index']);
    Route::get('/station-tags/add', [RadioStationTagController::class, 'add']);
    Route::get('/station-tags/edit/{id}', [RadioStationTagController::class, 'edit']);
    Route::post('/station-tags/save', [RadioStationTagController::class, 'save']);
    Route::delete('/station-tags/{id}', [RadioStationTagController::class, 'delete']);

    Route::get('/station-groups', [RadioStationGroupController::class, 'index']);
    Route::get('/station-groups/add', [RadioStationGroupController::class, 'add']);
    Route::get('/station-groups/edit/{id}', [RadioStationGroupController::class, 'edit']);
    Route::post('/station-groups/save', [RadioStationGroupController::class, 'save']);
    Route::delete('/station-groups/{id}', [RadioStationGroupController::class, 'delete']);

    Route::get('/custom-values', [AdminController::class, 'customValues']);
    Route::get('/custom-values/add', [AdminController::class, 'addValue']);
    Route::get('/custom-values/edit/{id}', [AdminController::class, 'editValue']);
    Route::post('/custom-values/save', [AdminController::class, 'saveValue']);
    Route::delete('/custom-values/{id}', [AdminController::class, 'deleteValue']);

});
