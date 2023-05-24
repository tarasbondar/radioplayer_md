<div class="item">
    <div class="logo">
        <img class="logo__bg"
             srcset="{{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}},
            {{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}} 2x"
             src="{{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}}"
             width="100%" alt="" loading="lazy">

        <img class="logo__img"
             srcset="{{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}},
            {{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}} 2x"
             src="{{ !empty($p['image']) ? 'uploads/podcasts_images/' . $p['image'] : "/img/podcast-placeholder.png"}}"
             idth="100%" alt="" loading="lazy">
    </div>
    <h3 class="x-small item__title">{{ $p['name'] }}</h3>
    {{--<button class="item__link" type="button" aria-label="Select podcast"></button>--}}
    <a class="item__link" type="button" aria-label="Select podcast" href="/podcasts/{{$p['id']}}/view"></a>

</div>
