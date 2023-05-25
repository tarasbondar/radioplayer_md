@foreach ($list as $item)
    @php
        $episode = $item->episode;
        $podcast = $episode->podcast;
        $image = $podcast->image ? '/uploads/podcasts_images/' . $podcast->image : null;
    @endphp
    <div class="np-modal__playing-list__item" data-player-playlist-item="{{ $episode->id }}" data-player-playlist-item-source="{{ $episode->source_url }}">
        <div class="btn btn_ico btn_sort">
            <svg class="icon">
                <use href="/img/sprite.svg#sort"></use>
            </svg>
        </div>
        <div class="info">
            <div class="avatar">
                <div class="avatar__action">
                    <svg class="icon">
                        <use href="/img/sprite.svg#play-bk"></use>
                    </svg>
                </div>
                @if ($image)
                    <img src="{{ $image }}" srcset="{{ $image }} 1x, {{ $image }} 2x" alt="img">
                @endif
            </div>
            <div class="content">
                <span class="np-modal__player-body__header__pretitle">{{ $podcast->name }}</span>
                <strong class="np-modal__player-body__header__title x-small">{{ $episode->name }}</strong>
            </div>
        </div>
        <button class="btn btn_ico btn_ico-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <svg class="icon">
                <use href="/img/sprite.svg#more-vertical"></use>
            </svg>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <span class="dropdown-item">
                    <svg class="icon ms-0">
                        <use href="/img/sprite.svg#chevron-up"></use>
                    </svg>
                    В начало
                </span>
            </li>
            <li>
                <span class="dropdown-item">
                    <svg class="icon ms-0">
                        <use href="/img/sprite.svg#chevron-down"></use>
                    </svg>
                    В конец
                </span>
            </li>
            <li>
                <button type="button" class="dropdown-item" data-add-to-playlist="{{ $episode->id  }}">
                    <svg class="icon ms-0">
                        <use href="/img/sprite.svg#x"></use>
                    </svg>
                    Убрать
                </button>
            </li>
        </ul>
    </div>
@endforeach
