<?php

namespace App\Services;

use App\Models\RadioStation;
use App\Models\RadioStationFavorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FavoriteService
{
    const cookieLifetime = 60*24*365;

    /**
     * Favorite station for unregistered user
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function favStationForUnregisteredUser($id): \Illuminate\Http\JsonResponse
    {
        $favorites = json_decode(Request::cookie('favorites'), true) ?? [];

        if (in_array($id, $favorites)) {
            // Remove from favorites
            $favorites = array_diff($favorites, [$id]);
            DB::table('radiostations_favorites_history')->insert(['station_id' => $id, 'user_id' => 0, 'action' => 0]);
            return response()->json(['action' => 'deleted', 'id' => $id])->cookie('favorites', json_encode($favorites), self::cookieLifetime);
        }

        // Add to favorites
        $favorites[] = $id;

        $station = RadioStation::find($id)->toArray();
        $output = view('partials.station-card', ['station' => $station, 'fav_stations' => $favorites])->render();
        DB::table('radiostations_favorites_history')->insert(['station_id' => $id, 'user_id' => 0, 'action' => 1]);
        return response()->json(['action' => 'added', 'output' => $output, 'id' => $id])->cookie('favorites', json_encode($favorites), self::cookieLifetime);
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

            DB::table('radiostations_favorites_history')->insert(['station_id' => $id, 'user_id' => Auth::id(), 'action' => 0]);
            return ['action' => 'deleted', 'id' => $id];
        }

        DB::table('radiostations_favorites')
            ->insert(['station_id' => $id, 'user_id' => Auth::id()]);

        $station = RadioStation::find($id)->toArray();
        $output = view('partials.station-card', ['station' => $station, 'fav_stations' => [$id]])->render();
        DB::table('radiostations_favorites_history')->insert(['station_id' => $id, 'user_id' => Auth::id(), 'action' => 1]);
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
            return RadioStationFavorite::where(['user_id' => Auth::id()])->get()->pluck('station_id')->toArray();
        }
        $favorites = Request::cookie('favorites');
        return $favorites ? json_decode($favorites, true) : [];
    }
}
