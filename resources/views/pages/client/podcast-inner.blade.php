@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcast__inner">
                        <div class="podcast__descr-block">
                            <div class="podcast-img">
                                <img src="img/img.png" srcset="img/img.png 1x, img/img@2.png 2x" alt="img">
                            </div>
                            <div class="text">
                                <span class="podcast__descr">@ArtistName</span>
                                <strong class="podcast__title">Podcast Title</strong>
                            </div>
                        </div>
                        <span class="podcast__data">17 февр.</span>
                        <strong class="podcast__title">Orci nulla adipiscing cursus eu quis</strong>
                        <div class="podcast__timer">
                            <div class="play">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_801_29988)">
                                        <path d="M2.5 1.5L9.5 6L2.5 10.5V1.5Z" fill="currentColor" stroke="currentColor" stroke-width="0.666667" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_801_29988">
                                            <rect width="12" height="12" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="pause">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 2H3V10H5V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 2H7V10H9V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span>00:37:54</span>
                        </div>
                        <p class="podcast__text">Eget elit nulla gravida cursus ac dignissim. Aenean accumsan tempor in pellentesque neque hendrerit quisque id. Vulputate quis blandit vulputate <a href="#">etiam mauris</a> auctor a orci duis. Dis quam eget justo nibh non feugiat proin.</p>
                        <ul class="list">
                            <li class="item">Tempor non dignissim quis ultricies</li>
                            <li class="item">Aliquet quis amet in consectetur</li>
                            <li class="item">Risus quisque vitae elementum</li>
                        </ul>
                        <p class="podcast__text">Dignissim mollis sollicitudin habitasse libero vehicula arcu nisi mauris venenatis. Ac ut habitant id pharetra. Proin mi venenatis nullam cras nibh bibendum. Iaculis vulputate massa felis augue neque felis sit ac. </p>
                    </div>

                </div>
            </div>
            <a href="#" class="btn btn_ico btn_ico-accent page-btn _playing" aria-label="Редактировать">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 1.99981C16.2626 1.73717 16.5744 1.52883 16.9176 1.38669C17.2608 1.24455 17.6286 1.17139 18 1.17139C18.3714 1.17139 18.7392 1.24455 19.0824 1.38669C19.4256 1.52883 19.7374 1.73717 20 1.99981C20.2626 2.26246 20.471 2.57426 20.6131 2.91742C20.7553 3.26058 20.8284 3.62838 20.8284 3.99981C20.8284 4.37125 20.7553 4.73905 20.6131 5.08221C20.471 5.42537 20.2626 5.73717 20 5.99981L6.5 19.4998L1 20.9998L2.5 15.4998L16 1.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </section>
    </main>

@endsection
