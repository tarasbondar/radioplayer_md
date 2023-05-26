<div class="item play-station" id="station-{{ $station['id'] }}">
    <div class="logo">
        <img class="logo__bg"
             srcset="{{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}},
             {{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}} 2x"
             src="{{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}}"
             width="100%" alt="" loading="lazy">
        <img class="logo__img"
             srcset="{{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}},
             {{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}} 2x"
             src="{{ !empty($station['image_logo']) ? 'uploads/stations_images/' . $station['image_logo'] : "/img/station-placeholder.png"}}"
             width="100%" alt="" loading="lazy">
    </div>
    <h3 class="x-small item__title">{{ $station['name'] }}</h3>
    <button class="item__link" type="button" id="station-{{ $station['id'] }}" aria-label="{{ __('client.playStation') }}" data-play-station="{{ $station['id']  }}" ></button>
    <button class="item__favourites-btn fav-station {{$station['favorited'] ? 'active' : ''}}" value="{{ $station['id'] }}"
            type="button" aria-label="{{$station['favorited'] ? __('client.removeFromFavourites') : __('client.addToFavourites') }}">
        <span class="item__favourites-btn__inner">
            <svg class="icon" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
            </svg>
        </span>
    </button>
</div>
