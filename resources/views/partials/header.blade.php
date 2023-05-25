<header class="header">
    <a class="header__logo" href="/">
        <picture>
            <source media="(min-width: 1200px)" srcset="/img/logo-large.svg">
            <img class="header__logo__img" src="/img/logo-small.svg" width="38" height="38" alt="Radio Player Moldova" loading="lazy">
        </picture>
    </a>

    <div class="header__nav">
        <ul class="header__nav__list">
            <li class="header__nav__list-item">
                <a class="btn btn_rows x-small {{ str_contains($_SERVER['REQUEST_URI'], 'station') || $_SERVER['REQUEST_URI'] == '/' ? 'active' : ''}} " href="/">
                    <svg class="icon">
                        <use href="/img/sprite.svg#radio"></use>
                    </svg>
                    <span class="btn__text">{{ __('app.radio') }}</span>
                </a>
            </li>
            <li class="header__nav__list-item">
                <a class="btn btn_rows x-small {{ str_contains($_SERVER['REQUEST_URI'], 'podcast') ? 'active' : ''}}" href="/all-podcasts">
                    <svg class="icon">
                        <use href="/img/sprite.svg#mic"></use>
                    </svg>
                    <span class="btn__text">{{ __('app.podcasts') }}</span>
                </a>
            </li>
        </ul>
    </div>

    <button class="btn btn_ico btn_ico-primary header__menu-btn" type="button" aria-label="Открыть меню" data-menu-open>
        <svg class="icon">
            <use href="/img/sprite.svg#menu-2"></use>
        </svg>
    </button>

    <div class="header__menu" data-menu>
        <button class="btn btn_ico header__menu__close-btn" type="button" aria-label="Close menu" data-menu-close>
            <svg class="icon">
                <use href="/img/sprite.svg#x"></use>
            </svg>
        </button>
        <div class="header__menu__inner">
            <div class="header__menu__options">
                <div class="header__menu__lang">
                    <a id='lang-ru' class="btn btn_large btn_switcher" href="#">RU</a>
                    <a id='lang-ro' class="btn btn_large btn_switcher" href="#">RO</a>
                    <a id='lang-en' class="btn btn_large btn_switcher" href="#">EN</a>
                </div>

                <button class="btn btn_large header__menu__theme-btn" type="button" aria-label="Switch Theme" data-theme-toggle>
                    <svg class="icon header__menu__theme-btn__ico-dark">
                        <use href="/img/sprite.svg#sun"></use>
                    </svg>
                    <svg class="icon header__menu__theme-btn__ico-light">
                        <use href="/img/sprite.svg#moon"></use>
                    </svg>
                </button>
            </div>

            <nav class="header__menu__nav">

                @guest
                    <div class="header__menu__row">
                        <a class="btn header__menu__link-panel" href="{{ route('login') }}">
                            <svg class="icon"><use href="/img/sprite.svg#log-in"></use></svg>
                            <span>Вход</span>
                        </a>

                        <a class="btn header__menu__link-panel" href="{{ route('register') }}">
                            <svg class="icon"><use href="/img/sprite.svg#key"></use></svg>
                            <span>{{ __('auth.registration') }}</span>
                        </a>
                    </div>
                @endguest

                @if(auth()->check() && auth()->user()->role < 1)
                    <a class="btn header__menu__link-panel" href="/apply">
                        <svg class="icon"><use href="/img/sprite.svg#mic"></use></svg>
                        <span>{{ __('client.becomeAuthor') }}</span>
                        <picture>
                            <source srcset="img/become-author-dark.png 1x, img/become-author-dark@2x.png 2x" media="(prefers-color-scheme: dark)">
                            <source srcset="img/become-author-light.png 1x, img/become-author-light@2x.png 2x" media="(prefers-color-scheme: light) or (prefers-color-scheme: no-preference)">
                            <img class="header__menu__link-panel__img" src="img/become-author-light.png" alt="" width="115" height="100" loading="lazy">
                        </picture>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->role > 0)
                    <a class="btn header__menu__link-panel header__menu__link-panel_highlighted" href="/my-podcasts">
                        <svg class="icon"><use href="/img/sprite.svg#mic"></use></svg>
                        <span>Мои подкасты</span>
                        <img class="header__menu__link-panel__img" srcset="/img/become-author-highlighted.png 1x, /img/become-author-highlighted@2x.png 2x" src="/img/become-author-highlighted.png" alt="" width="115" height="100" loading="lazy">
                    </a>
                @endif

                <a class="btn btn_list-item" href="/subscriptions">
                    <svg class="icon"><use href="/img/sprite.svg#check-circle"></use></svg>
                    <span>Подписки</span>
                </a>

                <a class="btn btn_list-item" href="/listen-later">
                    <svg class="icon"><use href="/img/sprite.svg#clock"></use></svg>
                    <span>Прослушать позже</span>
                </a>

                <a class="btn btn_list-item" href="/history">
                    <svg class="icon"><use href="/img/sprite.svg#rotate-ccw"></use></svg>
                    <span>История прослушивания</span>
                </a>

                <a class="btn btn_list-item" href="/downloaded">
                    <svg class="icon"><use href="/img/sprite.svg#download-cloud"></use></svg>
                    <span>Скачанные</span>
                </a>

                <hr class="header__menu__sep">

                <a class="btn btn_list-item" href="/settings">
                    <svg class="icon"><use href="/img/sprite.svg#sliders"></use></svg>
                    <span>Настройки</span>
                </a>

                @auth
                    <a class="btn btn_list-item" href="{{ route('logout') }}"  onclick="event.preventDefault(); $('#logout-form').submit();">
                        <svg class="icon"><use href="/img/sprite.svg#log-out"></use></svg>
                        <span>Выход</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endauth

                <hr class="header__menu__sep">

                <a class="btn btn_list-item" href="#" rel="nofollow noopener" target="_blank">
                    <svg class="icon">
                        <use href="/img/sprite.svg#facebook"></use>
                    </svg>
                    <span>Facebook</span>
                </a>

                <a class="btn btn_list-item" href="#" rel="nofollow noopener" target="_blank">
                    <svg class="icon">
                        <use href="/img/sprite.svg#instagram"></use>
                    </svg>
                    <span>Instagram</span>
                </a>
            </nav>

            <div class="header__menu__app-links">
                <a class="header__menu__app-links__link" href="!#" rel="nofollow noopener" target="_blank" aria-label="Загрузите в App Store">
                    <img class="header__menu__app-links__img header__menu__app-links__img_app-store" src="img/app_store-ru.svg" width="120" height="40" alt="App Store" loading="lazy">
                </a>

                <a class="header__menu__app-links__link" href="!#" rel="nofollow noopener" target="_blank" aria-label="Скачать из Google Play">
                    <img class="header__menu__app-links__img header__menu__app-links__img_google-play" src="img/google_play-ru.svg" width="135" height="40" alt="Google Play" loading="lazy">
                </a>
            </div>

            <div class="header__menu__footer x-small">
                Контакты для связи<br>
                <a class="header__menu__footer__link" href="mailto:support@dixi.md">support@dixi.md</a>, <a class="header__menu__footer__link" href="tel:+37322546723">+373 22 54 67 23</a>
                <hr class="header__menu__sep">
                <div class="header__menu__footer__copyright">© 2023 Radio Player</div>
                <div class="header__menu__footer__meta">Разработано в <a class="header__menu__footer__link" href="www.meta-sistem.md" rel="nofollow noopener" target="_blank">www.meta-sistem.md</a></div>
            </div>
        </div>
    </div>
    <div class="header__menu-backdrop" data-menu-backdrop></div>

    <script>

        (function(){
            let language = '{{ @$lang }}'; //or by cookie
            $('#lang-'+language).addClass('active');

            $(document).on('click', '.header__menu__lang > a:not(.active)', function(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/settings/change-language',
                    data: {'lang': $(this).html()},
                    success: function (response) {
                        if (response.length > 0) {
                            $('.header__menu__lang > a.active').removeClass('active');
                            $('#lang-'+response).addClass('active');
                            //cookie
                        }
                    }
                })
            })
        })(jQuery)

    </script>

</header>
