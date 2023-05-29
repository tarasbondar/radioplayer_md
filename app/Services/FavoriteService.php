<?php

namespace App\Services;

use App\Models\RadioStation;
use App\Models\RadiostationFavorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FavoriteService
{
    /**
     * Favorite station for unregistered user
     * @param $id
     * @return array
     */
    public static function favStationForUnregisteredUser($id): array
    {
        $favorites = Session::get('favorites', []);

        if (in_array($id, $favorites)) {
            // Remove from favorites
            $favorites = array_diff($favorites, [$id]);

            Session::put('favorites', $favorites);

            return ['action' => 'deleted', 'id' => $id];
        }

        // Add to favorites
        $favorites[] = $id;

        Session::put('favorites', $favorites);

        $station = RadioStation::find($id)->toArray();
        $output = view('partials.station-card', ['station' => $station, 'fav_stations' => $favorites])->render();

        return ['action' => 'added', 'output' => $output, 'id' => $id];
    }

    /**
     * Favorite station for registered user
     *
     * @param $id
     * @return array
     */
    public static function favStationForRegisteredUser($id)
    {
        $exists = DB::table('radiostations_favorites')
            ->where(['station_id' => $id, 'user_id' => Auth::id()])
            ->exists();

        if ($exists) {
            DB::table('radiostations_favorites')
                ->where(['station_id' => $id, 'user_id' => Auth::id()])
                ->delete();

            return ['action' => 'deleted', 'id' => $id];
        }

        DB::table('radiostations_favorites')
            ->insert(['station_id' => $id, 'user_id' => Auth::id()]);

        $station = RadioStation::find($id)->toArray();
        $output = view('partials.station-card', ['station' => $station, 'fav_stations' => [$id]])->render();

        return ['action' => 'added', 'output' => $output, 'id' => $id];
    }

    /**
     * Get favorites
     *
     * @return array
     */
    public static function getFavorites(): array
    {
        if (Auth::check()) {
            return RadiostationFavorite::where(['user_id' => Auth::id()])->get()->pluck('station_id')->toArray();
        }

        return Session::get('favorites', []);
    }
}
