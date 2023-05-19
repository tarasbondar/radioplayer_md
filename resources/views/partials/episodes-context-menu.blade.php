<ul class="dropdown-menu dropdown-menu-end">
    <li>
        <a class="dropdown-item" href="/episodes/{{$episode['id']}}/view">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Просмотреть
        </a>
    </li>

    @if(auth()->id() == $podcast['owner_id'])
        <li>
            <a class="dropdown-item" href="/edit-episode/{{$episode['id']}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 2.99981C17.2626 2.73717 17.5744 2.52883 17.9176 2.38669C18.2608 2.24455 18.6286 2.17139 19 2.17139C19.3714 2.17139 19.7392 2.24455 20.0824 2.38669C20.4256 2.52883 20.7374 2.73717 21 2.99981C21.2626 3.26246 21.471 3.57426 21.6131 3.91742C21.7553 4.26058 21.8284 4.62838 21.8284 4.99981C21.8284 5.37125 21.7553 5.73905 21.6131 6.08221C21.471 6.42537 21.2626 6.73717 21 6.99981L7.5 20.4998L2 21.9998L3.5 16.4998L17 2.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Редактировать
            </a>
        </li>
    @endif

    <li>
        <a class="dropdown-item download-episode" href="#">
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
            Скачать
        </a>
    </li>

    @if(auth()->id() == $podcast['owner_id'])
        <li>
            <a class="dropdown-item delete-episode" href="">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Удалить
            </a>
        </li>
    @endif
</ul>
