<div class="item">
    <div class="logo">
        <img class="logo__bg" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
        <img class="logo__img" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
    </div>
    <h3 class="x-small item__title">{{ $p['name'] }}</h3>
    {{--<button class="item__link" type="button" aria-label="Select podcast"></button>--}}
    <a class="item__link" type="button" aria-label="Select podcast" href="/podcasts/{{$p['id']}}/view"></a>

</div>
