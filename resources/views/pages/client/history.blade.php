@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcasts__header d-flex flex-row justify-content-between mb-24">
                        <h2 class="h2 mb-0">История</h2>
                        <a class="link clear-history">Очистить</a>
                    </div>
                    <ul class="podcast__list">

                        <li class="podcast__elem">
                            <div class="d-flex flex-row align-items-start mb-2 history-block">
                                <div class="podcast-img">
                                    <img src="img/img.png" srcset="img/img.png 1x, img/img@2.png 2x" alt="img">
                                </div>
                                <div class="wrap">
                                    <div class="podcast__elem-wrap">
                                        <span class="podcast__data">17 февр.</span>
                                        <span class="podcast__elem-name">Название подкаста</span>
                                    </div>
                                    <strong class="podcast__elem-title mb-0">Orci nulla adipiscing cursus eu quis</strong>
                                </div>
                            </div>

                            <p class="podcast__elem-text">Iaculis eget pretium semper et viverra quis gravida gravida diam. Diam imperdiet consectetur nibh faucibus</p>
                            <div class="podcast__holder">
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
                                    <div class="pause" >
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 2H3V10H5V2Z" fill="#0F0F0F" stroke="#0F0F0F" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9 2H7V10H9V2Z" fill="#0F0F0F" stroke="#0F0F0F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <span>Прослушано</span>
                                </div>
                                <span class="publication">Опубликован</span>
                                <ul class="list">
                                    <li class="item">
                                        <a href="#" class="link">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.25 16H17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9.25 11.5H17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9.25 20.5H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.25 20.5H22.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M20.5 22.75L20.5 18.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="item">
                                        <a href="#" class="link">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_801_45179)">
                                                    <path d="M16 23.5C20.1421 23.5 23.5 20.1421 23.5 16C23.5 11.8579 20.1421 8.5 16 8.5C11.8579 8.5 8.5 11.8579 8.5 16C8.5 20.1421 11.8579 23.5 16 23.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M16 11.5V16L19 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_801_45179">
                                                        <rect width="18" height="18" fill="currentColor" transform="translate(7 7)"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="item">
                                        <a href="#" class="link">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_801_45183)">
                                                    <path d="M13 19.75L16 22.75L19 19.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M16 16V22.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M22.6589 20.5674C23.311 20.1089 23.7999 19.4546 24.0549 18.6993C24.3098 17.9441 24.3175 17.1272 24.0767 16.3673C23.836 15.6074 23.3593 14.944 22.716 14.4734C22.0726 14.0027 21.296 13.7493 20.4989 13.7499H19.5539C19.3283 12.8709 18.9063 12.0544 18.3195 11.362C17.7327 10.6696 16.9965 10.1194 16.1663 9.75271C15.3361 9.38604 14.4335 9.21247 13.5266 9.24509C12.6196 9.2777 11.7318 9.51565 10.9301 9.94102C10.1284 10.3664 9.43358 10.9681 8.89805 11.7008C8.36252 12.4336 8.0002 13.2782 7.83836 14.1713C7.67651 15.0643 7.71937 15.9824 7.9637 16.8565C8.20802 17.7305 8.64746 18.5378 9.24891 19.2174" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_801_45183">
                                                        <rect width="18" height="18" fill="currentColor" transform="translate(7 7)"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                                <button class="btn dropdown-toggle podcast__elem-option" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 16.75C16.4142 16.75 16.75 16.4142 16.75 16C16.75 15.5858 16.4142 15.25 16 15.25C15.5858 15.25 15.25 15.5858 15.25 16C15.25 16.4142 15.5858 16.75 16 16.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 11.5C16.4142 11.5 16.75 11.1642 16.75 10.75C16.75 10.3358 16.4142 10 16 10C15.5858 10 15.25 10.3358 15.25 10.75C15.25 11.1642 15.5858 11.5 16 11.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 22C16.4142 22 16.75 21.6642 16.75 21.25C16.75 20.8358 16.4142 20.5 16 20.5C15.5858 20.5 15.25 20.8358 15.25 21.25C15.25 21.6642 15.5858 22 16 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Просмотреть</a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17 2.99981C17.2626 2.73717 17.5744 2.52883 17.9176 2.38669C18.2608 2.24455 18.6286 2.17139 19 2.17139C19.3714 2.17139 19.7392 2.24455 20.0824 2.38669C20.4256 2.52883 20.7374 2.73717 21 2.99981C21.2626 3.26246 21.471 3.57426 21.6131 3.91742C21.7553 4.26058 21.8284 4.62838 21.8284 4.99981C21.8284 5.37125 21.7553 5.73905 21.6131 6.08221C21.471 6.42537 21.2626 6.73717 21 6.99981L7.5 20.4998L2 21.9998L3.5 16.4998L17 2.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Редактировать</a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_820_11406)">
                                                <path d="M8 17L12 21L16 17" stroke="#0F0F0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 12V21" stroke="#0F0F0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M20.8812 18.0899C21.7505 17.4786 22.4025 16.6061 22.7424 15.5991C23.0824 14.5921 23.0926 13.503 22.7715 12.4898C22.4505 11.4766 21.815 10.592 20.9572 9.96449C20.0994 9.33697 19.064 8.9991 18.0012 8.99993H16.7412C16.4404 7.82781 15.8776 6.73918 15.0953 5.81601C14.3129 4.89285 13.3313 4.15919 12.2244 3.67029C11.1174 3.18138 9.914 2.94996 8.70468 2.99345C7.49536 3.03694 6.31167 3.3542 5.24271 3.92136C4.17375 4.48851 3.24738 5.29078 2.53334 6.26776C1.8193 7.24474 1.33621 8.37098 1.12041 9.56168C0.904624 10.7524 0.961764 11.9765 1.28753 13.142C1.6133 14.3074 2.19921 15.3837 3.00115 16.2899" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_820_11406">
                                                    <rect width="24" height="24" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        Скачать</a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ __('client.delete') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </section>
    </main>

    <script>

        (function(){
            $(document).on('click', '.clear-history', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/clear-history',
                    success: function () {
                        $('.podcast__list').html('');
                    }
                })
            })

        })(jQuery)

    </script>

@endsection
