<aside class="player" id="player-radio" data-player="radio">
    <div class="now-playing" data-now-playing>

        <input id="audio-source" type="text" value="{{ @$current['source'] }}" readonly hidden>
        <input id="audio-source-hd" type="text" value="{{ @$current['source_hd'] }}" readonly hidden>

        <button class="now-playing__btn" type="button" aria-label="Подробнее" data-np-trigger></button>
        <div class="now-playing__track">
            <div class="logo">
                <img class="logo__bg" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                <img class="logo__img"
                     srcset="{{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}} 1x,
                     {{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}} 2x"
                     src="{{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}}"
                     width="100%" alt="" loading="lazy">
            </div>
            <div class="now-playing__track__body">
                <div class="now-playing__track__author x-small">
                    <div class="scrolling-text" data-scrolling-text-container>
                        <div class="scrolling-text__inner" data-scrolling-text>
                            <span class="scrolling-text__data artist-name" data-scrolling-text-data>Artist name</span>
                        </div>
                    </div>
                </div>
                <div class="now-playing__track__title x-small">
                    <div class="scrolling-text" data-scrolling-text-container>
                        <div class="scrolling-text__inner" data-scrolling-text>
                            <span class="scrolling-text__data song-title" data-scrolling-text-data>Song title</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="now-playing__actions">
            <button class="btn btn_ico btn_ico-primary now-playing__fav-btn active" type="button" aria-label="Добавить в избранное">
                <svg class="icon" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                </svg>
            </button>
            <button class="btn btn_ico btn_ico-accent now-playing__play-btn active" type="button" aria-label="Пауза">
                <svg class="icon now-playing__play-btn__pause">
                    <use href="/img/sprite.svg#pause-bk"></use>
                </svg>
                <svg class="icon now-playing__play-btn__play">
                    <use href="/img/sprite.svg#play-bk"></use>
                </svg>
            </button>
        </div>
    </div>

    <div class="np-modal scrollbar" data-np-modal>
        <div class="np-modal__player" data-np-modal-player>
            <div class="np-modal__header">
                <button class="btn btn_ico btn_ico-primary" type="button" aria-label="Свернуть" data-np-modal-close>
                    <svg class="icon">
                        <use href="/img/sprite.svg#chevron-down"></use>
                    </svg>
                </button>
                <div class="np-modal__header__title h2">{{ $current['name'] }}</div>
            </div>

            <div class="np-modal__player-body">
                <div class="np-modal__player-body__header">
                    <div class="logo">
                        <img class="logo__bg" srcset="/img/radio-logo.png 1x, img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                        <img class="logo__img"
                             srcset="{{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}} 1x,
                             {{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}} 2x"
                             src="{{ !empty($current['image_logo']) ? 'uploads/stations_images/' . $current['image_logo'] : "/img/station-placeholder.png"}}"
                             width="100%" alt="" loading="lazy">
                    </div>
                    <div class="np-modal__player-body__header__inner">
                        <div class="np-modal__player-body__header__pretitle x-small">Song title</div>
                        <div class="np-modal__player-body__header__pretitle x-small">Artist name</div>
                        <div class="np-modal__player-body__header__title h2">{{ $current['name'] }}</div>
                    </div>
                </div>

                <div class="np-modal__player-body__main-actions">
                    <svg class="np-modal__player-body__main-actions__wave" width="314" height="38" viewBox="0 0 314 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M157 0C156.448 0 156 0.447716 156 1V37C156 37.5523 156.448 38 157 38C157.552 38 158 37.5523 158 37V1C158 0.447715 157.552 0 157 0ZM181 6C180.448 6 180 6.44772 180 7V31C180 31.5523 180.448 32 181 32C181.552 32 182 31.5523 182 31V7C182 6.44772 181.552 6 181 6ZM177 10C176.448 10 176 10.4477 176 11V27C176 27.5523 176.448 28 177 28C177.552 28 178 27.5523 178 27V11C178 10.4477 177.552 10 177 10ZM172 16C172 15.4477 172.448 15 173 15C173.552 15 174 15.4477 174 16V22C174 22.5523 173.552 23 173 23C172.448 23 172 22.5523 172 22V16ZM196 17C196 16.4477 196.448 16 197 16C197.552 16 198 16.4477 198 17V21C198 21.5523 197.552 22 197 22C196.448 22 196 21.5523 196 21V17ZM221 17C220.448 17 220 17.4477 220 18V20C220 20.5523 220.448 21 221 21C221.552 21 222 20.5523 222 20V18C222 17.4477 221.552 17 221 17ZM244 18C244 17.4477 244.448 17 245 17C245.552 17 246 17.4477 246 18V20C246 20.5523 245.552 21 245 21C244.448 21 244 20.5523 244 20V18ZM269 17C268.448 17 268 17.4477 268 18V20C268 20.5523 268.448 21 269 21C269.552 21 270 20.5523 270 20V18C270 17.4477 269.552 17 269 17ZM292 18C292 17.4477 292.448 17 293 17C293.552 17 294 17.4477 294 18V20C294 20.5523 293.552 21 293 21C292.448 21 292 20.5523 292 20V18ZM189 15C188.448 15 188 15.4477 188 16V22C188 22.5523 188.448 23 189 23C189.552 23 190 22.5523 190 22V16C190 15.4477 189.552 15 189 15ZM212 17C212 16.4477 212.448 16 213 16C213.552 16 214 16.4477 214 17V21C214 21.5523 213.552 22 213 22C212.448 22 212 21.5523 212 21V17ZM237 17C236.448 17 236 17.4477 236 18V20C236 20.5523 236.448 21 237 21C237.552 21 238 20.5523 238 20V18C238 17.4477 237.552 17 237 17ZM260 18C260 17.4477 260.448 17 261 17C261.552 17 262 17.4477 262 18V20C262 20.5523 261.552 21 261 21C260.448 21 260 20.5523 260 20V18ZM285 17C284.448 17 284 17.4477 284 18V20C284 20.5523 284.448 21 285 21C285.552 21 286 20.5523 286 20V18C286 17.4477 285.552 17 285 17ZM308 18C308 17.4477 308.448 17 309 17C309.552 17 310 17.4477 310 18V20C310 20.5523 309.552 21 309 21C308.448 21 308 20.5523 308 20V18ZM200 14C200 13.4477 200.448 13 201 13C201.552 13 202 13.4477 202 14V24C202 24.5523 201.552 25 201 25C200.448 25 200 24.5523 200 24V14ZM225 15C224.448 15 224 15.4477 224 16V22C224 22.5523 224.448 23 225 23C225.552 23 226 22.5523 226 22V16C226 15.4477 225.552 15 225 15ZM248 16C248 15.4477 248.448 15 249 15C249.552 15 250 15.4477 250 16V22C250 22.5523 249.552 23 249 23C248.448 23 248 22.5523 248 22V16ZM273 15C272.448 15 272 15.4477 272 16V22C272 22.5523 272.448 23 273 23C273.552 23 274 22.5523 274 22V16C274 15.4477 273.552 15 273 15ZM296 16C296 15.4477 296.448 15 297 15C297.552 15 298 15.4477 298 16V22C298 22.5523 297.552 23 297 23C296.448 23 296 22.5523 296 22V16ZM185 10C184.448 10 184 10.4477 184 11V27C184 27.5523 184.448 28 185 28C185.552 28 186 27.5523 186 27V11C186 10.4477 185.552 10 185 10ZM208 14C208 13.4477 208.448 13 209 13C209.552 13 210 13.4477 210 14V24C210 24.5523 209.552 25 209 25C208.448 25 208 24.5523 208 24V14ZM233 15C232.448 15 232 15.4477 232 16V22C232 22.5523 232.448 23 233 23C233.552 23 234 22.5523 234 22V16C234 15.4477 233.552 15 233 15ZM256 16C256 15.4477 256.448 15 257 15C257.552 15 258 15.4477 258 16V22C258 22.5523 257.552 23 257 23C256.448 23 256 22.5523 256 22V16ZM281 15C280.448 15 280 15.4477 280 16V22C280 22.5523 280.448 23 281 23C281.552 23 282 22.5523 282 22V16C282 15.4477 281.552 15 281 15ZM304 16C304 15.4477 304.448 15 305 15C305.552 15 306 15.4477 306 16V22C306 22.5523 305.552 23 305 23C304.448 23 304 22.5523 304 22V16ZM204 11C204 10.4477 204.448 10 205 10C205.552 10 206 10.4477 206 11V27C206 27.5523 205.552 28 205 28C204.448 28 204 27.5523 204 27V11ZM229 13C228.448 13 228 13.4477 228 14V24C228 24.5523 228.448 25 229 25C229.552 25 230 24.5523 230 24V14C230 13.4477 229.552 13 229 13ZM252 14C252 13.4477 252.448 13 253 13C253.552 13 254 13.4477 254 14V24C254 24.5523 253.552 25 253 25C252.448 25 252 24.5523 252 24V14ZM277 13C276.448 13 276 13.4477 276 14V24C276 24.5523 276.448 25 277 25C277.552 25 278 24.5523 278 24V14C278 13.4477 277.552 13 277 13ZM300 14C300 13.4477 300.448 13 301 13C301.552 13 302 13.4477 302 14V24C302 24.5523 301.552 25 301 25C300.448 25 300 24.5523 300 24V14ZM193 18C192.448 18 192 18.4477 192 19C192 19.5523 192.448 20 193 20C193.552 20 194 19.5523 194 19C194 18.4477 193.552 18 193 18ZM216 19C216 18.4477 216.448 18 217 18C217.552 18 218 18.4477 218 19C218 19.5523 217.552 20 217 20C216.448 20 216 19.5523 216 19ZM241 18C240.448 18 240 18.4477 240 19C240 19.5523 240.448 20 241 20C241.552 20 242 19.5523 242 19C242 18.4477 241.552 18 241 18ZM264 19C264 18.4477 264.448 18 265 18C265.552 18 266 18.4477 266 19C266 19.5523 265.552 20 265 20C264.448 20 264 19.5523 264 19ZM289 18C288.448 18 288 18.4477 288 19C288 19.5523 288.448 20 289 20C289.552 20 290 19.5523 290 19C290 18.4477 289.552 18 289 18ZM312 19C312 18.4477 312.448 18 313 18C313.552 18 314 18.4477 314 19C314 19.5523 313.552 20 313 20C312.448 20 312 19.5523 312 19ZM141 15C140.448 15 140 15.4477 140 16V22C140 22.5523 140.448 23 141 23C141.552 23 142 22.5523 142 22V16C142 15.4477 141.552 15 141 15ZM116 17C116 16.4477 116.448 16 117 16C117.552 16 118 16.4477 118 17V21C118 21.5523 117.552 22 117 22C116.448 22 116 21.5523 116 21V17ZM93 17C92.4477 17 92 17.4477 92 18V20C92 20.5523 92.4477 21 93 21C93.5523 21 94 20.5523 94 20V18C94 17.4477 93.5523 17 93 17ZM68 18C68 17.4477 68.4477 17 69 17C69.5523 17 70 17.4477 70 18V20C70 20.5523 69.5523 21 69 21C68.4477 21 68 20.5523 68 20V18ZM45 17C44.4477 17 44 17.4477 44 18V20C44 20.5523 44.4477 21 45 21C45.5523 21 46 20.5523 46 20V18C46 17.4477 45.5523 17 45 17ZM20 18C20 17.4477 20.4477 17 21 17C21.5523 17 22 17.4477 22 18V20C22 20.5523 21.5523 21 21 21C20.4477 21 20 20.5523 20 20V18ZM125 15C124.448 15 124 15.4477 124 16V22C124 22.5523 124.448 23 125 23C125.552 23 126 22.5523 126 22V16C126 15.4477 125.552 15 125 15ZM100 17C100 16.4477 100.448 16 101 16C101.552 16 102 16.4477 102 17V21C102 21.5523 101.552 22 101 22C100.448 22 100 21.5523 100 21V17ZM77 17C76.4477 17 76 17.4477 76 18V20C76 20.5523 76.4477 21 77 21C77.5523 21 78 20.5523 78 20V18C78 17.4477 77.5523 17 77 17ZM52 18C52 17.4477 52.4477 17 53 17C53.5523 17 54 17.4477 54 18V20C54 20.5523 53.5523 21 53 21C52.4477 21 52 20.5523 52 20V18ZM29 17C28.4477 17 28 17.4477 28 18V20C28 20.5523 28.4477 21 29 21C29.5523 21 30 20.5523 30 20V18C30 17.4477 29.5523 17 29 17ZM4 18C4 17.4477 4.44772 17 5 17C5.55228 17 6 17.4477 6 18V20C6 20.5523 5.55228 21 5 21C4.44772 21 4 20.5523 4 20V18ZM137 10C136.448 10 136 10.4477 136 11V27C136 27.5523 136.448 28 137 28C137.552 28 138 27.5523 138 27V11C138 10.4477 137.552 10 137 10ZM112 14C112 13.4477 112.448 13 113 13C113.552 13 114 13.4477 114 14V24C114 24.5523 113.552 25 113 25C112.448 25 112 24.5523 112 24V14ZM89 15C88.4477 15 88 15.4477 88 16V22C88 22.5523 88.4477 23 89 23C89.5523 23 90 22.5523 90 22V16C90 15.4477 89.5523 15 89 15ZM64 16C64 15.4477 64.4477 15 65 15C65.5523 15 66 15.4477 66 16V22C66 22.5523 65.5523 23 65 23C64.4477 23 64 22.5523 64 22V16ZM41 15C40.4477 15 40 15.4477 40 16V22C40 22.5523 40.4477 23 41 23C41.5523 23 42 22.5523 42 22V16C42 15.4477 41.5523 15 41 15ZM16 16C16 15.4477 16.4477 15 17 15C17.5523 15 18 15.4477 18 16V22C18 22.5523 17.5523 23 17 23C16.4477 23 16 22.5523 16 22V16ZM129 10C128.448 10 128 10.4477 128 11V27C128 27.5523 128.448 28 129 28C129.552 28 130 27.5523 130 27V11C130 10.4477 129.552 10 129 10ZM104 14C104 13.4477 104.448 13 105 13C105.552 13 106 13.4477 106 14V24C106 24.5523 105.552 25 105 25C104.448 25 104 24.5523 104 24V14ZM81 15C80.4477 15 80 15.4477 80 16V22C80 22.5523 80.4477 23 81 23C81.5523 23 82 22.5523 82 22V16C82 15.4477 81.5523 15 81 15ZM56 16C56 15.4477 56.4477 15 57 15C57.5523 15 58 15.4477 58 16V22C58 22.5523 57.5523 23 57 23C56.4477 23 56 22.5523 56 22V16ZM33 15C32.4477 15 32 15.4477 32 16V22C32 22.5523 32.4477 23 33 23C33.5523 23 34 22.5523 34 22V16C34 15.4477 33.5523 15 33 15ZM8 16C8 15.4477 8.44771 15 9 15C9.55229 15 10 15.4477 10 16V22C10 22.5523 9.55229 23 9 23C8.44771 23 8 22.5523 8 22V16ZM133 6C132.448 6 132 6.44772 132 7V31C132 31.5523 132.448 32 133 32C133.552 32 134 31.5523 134 31V7C134 6.44772 133.552 6 133 6ZM108 11C108 10.4477 108.448 10 109 10C109.552 10 110 10.4477 110 11V27C110 27.5523 109.552 28 109 28C108.448 28 108 27.5523 108 27V11ZM85 13C84.4477 13 84 13.4477 84 14V24C84 24.5523 84.4477 25 85 25C85.5523 25 86 24.5523 86 24V14C86 13.4477 85.5523 13 85 13ZM60 14C60 13.4477 60.4477 13 61 13C61.5523 13 62 13.4477 62 14V24C62 24.5523 61.5523 25 61 25C60.4477 25 60 24.5523 60 24V14ZM37 13C36.4477 13 36 13.4477 36 14V24C36 24.5523 36.4477 25 37 25C37.5523 25 38 24.5523 38 24V14C38 13.4477 37.5523 13 37 13ZM12 14C12 13.4477 12.4477 13 13 13C13.5523 13 14 13.4477 14 14V24C14 24.5523 13.5523 25 13 25C12.4477 25 12 24.5523 12 24V14ZM121 18C120.448 18 120 18.4477 120 19C120 19.5523 120.448 20 121 20C121.552 20 122 19.5523 122 19C122 18.4477 121.552 18 121 18ZM96 19C96 18.4477 96.4477 18 97 18C97.5523 18 98 18.4477 98 19C98 19.5523 97.5523 20 97 20C96.4477 20 96 19.5523 96 19ZM73 18C72.4477 18 72 18.4477 72 19C72 19.5523 72.4477 20 73 20C73.5523 20 74 19.5523 74 19C74 18.4477 73.5523 18 73 18ZM48 19C48 18.4477 48.4477 18 49 18C49.5523 18 50 18.4477 50 19C50 19.5523 49.5523 20 49 20C48.4477 20 48 19.5523 48 19ZM25 18C24.4477 18 24 18.4477 24 19C24 19.5523 24.4477 20 25 20C25.5523 20 26 19.5523 26 19C26 18.4477 25.5523 18 25 18ZM0 19C0 18.4477 0.447715 18 1 18C1.55228 18 2 18.4477 2 19C2 19.5523 1.55228 20 1 20C0.447715 20 0 19.5523 0 19ZM160 7C160 6.44772 160.448 6 161 6C161.552 6 162 6.44772 162 7V31C162 31.5523 161.552 32 161 32C160.448 32 160 31.5523 160 31V7ZM165 13C164.448 13 164 13.4477 164 14V24C164 24.5523 164.448 25 165 25C165.552 25 166 24.5523 166 24V14C166 13.4477 165.552 13 165 13ZM168 19C168 18.4477 168.448 18 169 18C169.552 18 170 18.4477 170 19C170 19.5523 169.552 20 169 20C168.448 20 168 19.5523 168 19ZM145 18C144.448 18 144 18.4477 144 19C144 19.5523 144.448 20 145 20C145.552 20 146 19.5523 146 19C146 18.4477 145.552 18 145 18ZM148 14C148 13.4477 148.448 13 149 13C149.552 13 150 13.4477 150 14V24C150 24.5523 149.552 25 149 25C148.448 25 148 24.5523 148 24V14ZM153 6C152.448 6 152 6.44772 152 7V31C152 31.5523 152.448 32 153 32C153.552 32 154 31.5523 154 31V7C154 6.44772 153.552 6 153 6Z" fill="url(#wave)"/>
                        <defs>
                            <linearGradient id="wave" x1="1.63352e-06" y1="19" x2="314" y2="19" gradientUnits="userSpaceOnUse">
                                <stop stop-color="var(--wave-stop-color-1)"/>
                                <stop offset="0.5" stop-color="var(--wave-stop-color-2)"/>
                                <stop offset="1" stop-color="var(--wave-stop-color-3)"/>
                            </linearGradient>
                        </defs>
                    </svg>

                    <div class="np-modal__player-body__main-actions__inner">
                        <button class="btn btn_ico btn_ico-accent now-playing__play-btn active" data-play-button id="play-button" type="button" aria-label="Пауза">
                            <svg class="icon now-playing__play-btn__pause player-pause" hidden>
                                <use href="/img/sprite.svg#pause-bk"></use>
                            </svg>
                            <svg class="icon now-playing__play-btn__play player-play">
                                <use href="/img/sprite.svg#play-bk"></use>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="np-modal__player-body__secondary-actions">
                    <button class="btn btn_ico btn_ico-primary" type="button" aria-label="Поделиться">
                        <svg class="icon">
                            <use href="/img/sprite.svg#share-2"></use>
                        </svg>
                    </button>

                    <button class="btn btn_ico btn_ico-primary np-modal__btn-quality" type="button" aria-label="Качество" {{ empty($current['source_hd']) ? 'disabled' : '' }} data-change-source>
                        <svg class="icon"><use href="/img/sprite.svg#q"></use></svg>
                    </button>

                    <button class="btn btn_ico btn_ico-primary np-modal__btn-favourites fav-station {{$favorited ? 'active' : ''}}" type="button" value="{{ $current['id'] }}"
                            aria-label="{{$favorited ? "Убрать из избранного" : "Добавить в избранное"}}">
                        <svg class="icon" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                        </svg>
                    </button>

                    <button class="btn btn_ico btn_ico-primary np-modal__btn-timer" type="button" aria-label="Таймер выключения" data-np-modal-timer-trigger>
                        <svg class="icon"><use href="/img/sprite.svg#timer-3"></use></svg>
                        <span class="np-modal__btn-timer__time x-small hidden" data-timer-active-time>00:15</span>
                    </button>

                    <div class="dropup-center dropup np-modal__btn-volume">
                        <button class="btn btn_ico btn_ico-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" aria-label="Громкость">
                            <svg class="icon">
                                <use href="/img/sprite.svg#volume-2"></use>
                            </svg>
                        </button>

                        <div class="dropdown-menu">
                            <div class="volume-slider" data-volume-widget>
                                <div class="volume-slider__track" data-volume-track>
                                    <div class="volume-slider__progress" data-volume-progress style="height: 50%;">
                                        <button class="volume-slider__btn" data-volume-button type="button" tabindex="-1" aria-label="Изменить громкость"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="np-modal__player-more-list">
                @foreach($all as $station)
                    @include('partials.station-card-small', ['station' => $station])
                @endforeach
            </div>
        </div>

        <div class="np-modal__timer" data-np-modal-timer>
            <div class="np-modal__header">
                <button class="btn btn_ico btn_ico-primary np-modal__header__close-modal" type="button" aria-label="Свернуть" data-np-modal-close>
                    <svg class="icon">
                        <use href="/img/sprite.svg#chevron-down"></use>
                    </svg>
                </button>
                <div class="np-modal__header__title h2">{{ __('timer.timer') }}</div>
                <button class="btn btn_ico btn_ico-primary" type="button" aria-label="Закрыть таймер" data-np-modal-timer-close>
                    <svg class="icon">
                        <use href="/img/sprite.svg#x"></use>
                    </svg>
                </button>
            </div>

            <div class="np-modal__timer__inner">
                <div class="np-modal__timer__title">{{ __('timer.stop_after') }}</div>
                <div class="np-modal__timer__form">
                    <div class="np-modal__timer__time-input">
                        <button class="btn btn_ico btn_ico-primary" data-timer-change-hours="up" type="button" aria-label="{{ __('timer.increase_timer') }}">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-up"></use>
                            </svg>
                        </button>
                        <div class="np-modal__timer__values">
                            <div class="np-modal__timer__value h1" data-timer-hours>00</div>
                            <div class="np-modal__timer__label x-small">{{ __('timer.hours') }}</div>
                        </div>
                        <button class="btn btn_ico btn_ico-primary" data-timer-change-hours="down" type="button" aria-label="{{ __('timer.decrease_timer') }}">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-down"></use>
                            </svg>
                        </button>
                    </div>

                    <div class="np-modal__timer__time-input">
                        <button class="btn btn_ico btn_ico-primary" data-timer-change-minutes="up" type="button" aria-label="{{ __('timer.increase_timer') }}">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-up"></use>
                            </svg>
                        </button>
                        <div class="np-modal__timer__values">
                            <div class="np-modal__timer__value h1" data-timer-minutes>15</div>
                            <div class="np-modal__timer__label x-small">{{ __('timer.minutes') }}</div>
                        </div>
                        <button class="btn btn_ico btn_ico-primary" data-timer-change-minutes="down" type="button" aria-label="{{ __('timer.decrease_timer') }}">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-down"></use>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="np-modal__timer__actions">
                    <button class="btn btn_secondary btn_large" data-timer-reset type="button">{{ __('timer.reset') }}</button>
                    <button class="btn btn_primary btn_large" data-timer-apply type="button">{{ __('timer.apply') }}</button>
                </div>
            </div>

        </div>

    </div>
</aside>
