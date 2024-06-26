@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcast__inner">
                        <div class="podcast__descr-block">
                            <div class="podcast-img podcast-img_big">
                                <img src="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}}"
                                     srcset="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 1x,
                                     {{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 2x" alt="img">
                            </div>
                            <div class="text">
                                <span class="podcast__descr">{{ $podcast['username'] }}</span>
                                <span class="podcast__descr"> {{ $podcast['name'] }} </span>
                                <strong class="podcast__title mb-3">{{ $episode['name'] }}</strong>
                                @if (!auth()->check() || $podcast['subbed'] == 0)
                                    @include('partials.sub-button')
                                @else
                                    @include('partials.unsub-button')
                                @endif
                            </div>
                        </div>
                        <span class="podcast__data">{{ $episode['created_diff'] }}</span>
                        <strong class="podcast__title"> {{ $episode['name'] }} </strong>
                        <p class="podcast__text">{{ $podcast['description'] }}</p>

                        <div class="podcast__holder mb-24">
                            <div class="podcast__timer mb-0">
                                <div class="play">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_801_29988)">
                                            <path d="M2.5 1.5L9.5 6L2.5 10.5V1.5Z" fill="currentColor" stroke="currentColor" stroke-width="0.666667" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_801_29988">
                                                <rect width="12" height="12" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="pause">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 2H3V10H5V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9 2H7V10H9V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <span>00:37:54</span>
                            </div>
                            <ul class="list">
                                <li class="item">
                                    <a href="#" class="link">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.25 16H17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M9.25 11.5H17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M9.25 20.5H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M18.25 20.5H22.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M20.5 22.75L20.5 18.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="item">
                                    <a href="#" class="link">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_801_45179)">
                                                <path d="M16 23.5C20.1421 23.5 23.5 20.1421 23.5 16C23.5 11.8579 20.1421 8.5 16 8.5C11.8579 8.5 8.5 11.8579 8.5 16C8.5 20.1421 11.8579 23.5 16 23.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M16 11.5V16L19 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_801_45179">
                                                    <rect width="18" height="18" fill="currentColor" transform="translate(7 7)"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </a>
                                </li>
                                <li class="item">
                                    <a href="#" class="link">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_801_45183)">
                                                <path d="M13 19.75L16 22.75L19 19.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M16 16V22.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M22.6589 20.5674C23.311 20.1089 23.7999 19.4546 24.0549 18.6993C24.3098 17.9441 24.3175 17.1272 24.0767 16.3673C23.836 15.6074 23.3593 14.944 22.716 14.4734C22.0726 14.0027 21.296 13.7493 20.4989 13.7499H19.5539C19.3283 12.8709 18.9063 12.0544 18.3195 11.362C17.7327 10.6696 16.9965 10.1194 16.1663 9.75271C15.3361 9.38604 14.4335 9.21247 13.5266 9.24509C12.6196 9.2777 11.7318 9.51565 10.9301 9.94102C10.1284 10.3664 9.43358 10.9681 8.89805 11.7008C8.36252 12.4336 8.0002 13.2782 7.83836 14.1713C7.67651 15.0643 7.71937 15.9824 7.9637 16.8565C8.20802 17.7305 8.64746 18.5378 9.24891 19.2174" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_801_45183">
                                                    <rect width="18" height="18" fill="currentColor" transform="translate(7 7)"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                            <button class="btn dropdown-toggle podcast__elem-option" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 16.75C16.4142 16.75 16.75 16.4142 16.75 16C16.75 15.5858 16.4142 15.25 16 15.25C15.5858 15.25 15.25 15.5858 15.25 16C15.25 16.4142 15.5858 16.75 16 16.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16 11.5C16.4142 11.5 16.75 11.1642 16.75 10.75C16.75 10.3358 16.4142 10 16 10C15.5858 10 15.25 10.3358 15.25 10.75C15.25 11.1642 15.5858 11.5 16 11.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16 22C16.4142 22 16.75 21.6642 16.75 21.25C16.75 20.8358 16.4142 20.5 16 20.5C15.5858 20.5 15.25 20.8358 15.25 21.25C15.25 21.6642 15.5858 22 16 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>

                            @include('partials.episodes-context-menu')

                        </div>
                        <span class="podcast__text"> </span>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
