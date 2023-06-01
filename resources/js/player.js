import Sortable from 'sortablejs';
import { tooltips } from './_tooltips';

export let player = {
    volume: 0.5,
    playbackRate: 1,
    playbackRateMax: 2,
    playbackRateStep: 0.2,
    sourceType: 'sd',
    timer: {
        minutes: 15,
        hours: 0,
        seconds: 0,
        minutesStep: 1,
        hoursStep: 1,
        isActive: false,
        interval: null,
        intervalHours: 0,
        intervalMinutes: 0,
    },
    playlist: [],
    autoplay: true,
    stationId: null,
    episodeId: null,
    playerOpenedMob: false,
    lastSavedTime: 0,
    initialSaveTime: 0,
    lastRadioInfoTime: 0,
    playerType: 'radio',
    init(){
        this.initVolumeSlider();
        this.initEvents();
    },
    createPlayer(){
        if (typeof window.audio === 'undefined'){
            window.audio = document.createElement("audio");
            window.audio.addEventListener('timeupdate', this.updateProgressBar);
            window.audio.addEventListener('pause', this.updateProgressBar);
            window.audio.addEventListener('ended', this.updateProgressBar);
        }

    },

    timerShow(){
        $('[data-np-modal-player]').addClass('hidden');
        $('[data-np-modal-timer]').removeClass('hidden').addClass('open');

        this.timer.hours = this.timer.intervalHours;
        if (this.timer.intervalMinutes)
            this.timer.minutes = this.timer.intervalMinutes + 1;
        this.timerRenderTimes();
    },
    timerClose(){
        $('[data-np-modal-player]').removeClass('hidden');
        $('[data-np-modal-timer]').addClass('hidden').removeClass('open');
    },
    timerReset(){
        this.timer.isActive = false;
        this.timer.minutes = 15;
        this.timer.hours = 0;
        if (this.timer.interval !== null)
            clearInterval(this.timer.interval);
        $('[data-timer-active-time]').addClass('hidden');
        this.timerClose();
    },
    timerApply(timerContinue = false){
        if (!timerContinue)
            this.timer.seconds = this.timer.hours * 60 * 60 + this.timer.minutes * 60;
        this.timer.isActive = true;
        $('[data-timer-active-time]').text(String(this.timer.hours).padStart(2, '0') + ':' + String(this.timer.minutes).padStart(2, '0'));
        $('[data-timer-active-time]').removeClass('hidden');
        if (this.timer.interval !== null)
            clearInterval(this.timer.interval);
        this.intervalMinutes = this.timer.minutes;
        this.intervalHours = this.timer.hours;
        this.timer.interval = setInterval(() => {
            if (this.timer.seconds === 0) {
                clearInterval(this.timer.interval);
                this.timerReset();
                window.audio.pause();
                $('.player-play').show();
                $('.player-pause').hide();
            } else {
                this.timer.seconds--;
                this.timer.intervalHours = Math.floor((this.timer.seconds + 60) / 60 / 60);
                this.timer.intervalMinutes = Math.floor(this.timer.seconds / 60) - this.timer.intervalHours * 60;
                $('[data-timer-active-time]').text(String(this.timer.intervalHours).padStart(2, '0') + ':' + String(this.timer.intervalMinutes + 1).padStart(2, '0'));
                // send every 5 seconds info to server
            }
            //console.log(this.timer.seconds)
        }, 1000);

        this.timerClose();
    },
    saveWatchTime(isEnded = false){
        let self = this;
        if (self.episodeId === null)
            return;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/save-watch-time',
            data: {
                episode_id: self.episodeId,
                time: (isEnded) ? 0 : window.audio.currentTime
            },
            success: function (response) {
                if (response.status === 'success'){
                    if (!isEnded && !response.is_listened){
                        $('[data-play-episode="'+ response.episode_id +'"]').addClass('active');
                        $('[data-play-episode="'+ response.episode_id +'"] [data-is_listened="0"]').removeClass('hidden');
                        $('[data-play-episode="'+ response.episode_id +'"] [data-is_listened="1"]').addClass('hidden');
                    }

                    $('[data-duration-left="'+ response.episode_id +'"]').html(response.duration_left_label);
                }
            }
        })
    },
    setListened(episodeId){
        let self = this;
        if (self.episodeId === null) {
            return;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/record-history',
            data: {
                id: episodeId,
            },
            success: function (response) {
                if (response.status === 'success'){
                    $('[data-play-episode="'+ response.episode_id +'"]').removeClass('active').addClass('listened');
                    $('[data-play-episode="'+ response.episode_id +'"] [data-is_listened="0"]').addClass('hidden');
                    $('[data-play-episode="'+ response.episode_id +'"] [data-is_listened="1"]').removeClass('hidden');
                }
            }
        })
    },
    changeStation(id){
        let self = this;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '/play-station/' + id,
            success: function (response) {
                $('[data-player]').remove();
                if ($('.page-grid').length) {
                    $('.page-grid').prepend(response);
                    tooltips();
                }
                else {
                    $('body').append(response);
                    tooltips();
                }
                $('body').addClass('player-open');
                self.createPlayer();
                self.setSource($('#audio-source').val());
                self.stationId = id;
                if (self.timer.isActive)
                    self.timerApply(true);
                self.changePlayIcon();
                self.lastSavedTime = 0;
                self.initialSaveTime = 0;
                self.lastRadioInfoTime = 0;
            }
        })
    },
    changeEpisode(id){
        let self = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '/play-episode/' + id,
            success: function (response) {
                $('[data-player]').remove();
                if ($('.page-grid').length) {
                    $('.page-grid').prepend(response);
                    tooltips();
                } else {
                    $('body').append(response);
                    tooltips();
                }
                $('body').addClass('player-open')
                self.createPlayer();
                self.setSource($('#audio-source').val());
                self.episodeId = id;
                $('[data-player-playlist-item]').removeClass('active');
                $('[data-player-playlist-item="'+ self.episodeId +'"]').addClass('active');

                if (self.timer.isActive)
                    self.timerApply(true);
                self.changePlaybackRate(self.playbackRate);
                self.changePlayIcon();
                if (self.autoplay)
                    self.play(true);
                self.initSortable();
                if (self.playerOpenedMob){
                    $('[data-player]').addClass('open');
                }

                self.lastSavedTime = 0;
                self.initialSaveTime = 0;
                self.lastRadioInfoTime = 0;
            }
        })
    },
    initSortable(){
        let self = this;
        Sortable.create($('[data-player-playlist]').get(0), {
            onEnd: function (e) {
                self.savePlaylistSorting();
            }
        });
    },
    play(forcePlay = false){
        let startTime = $('#start-time').val();
        if (window.audio.paused || forcePlay) {
            if (startTime !== undefined && startTime !== null && startTime !== '')
                window.audio.currentTime = startTime;
            window.audio.play();
        } else {
            window.audio.pause();
        }
        this.setVolume(this.volume);
        this.changePlayIcon();
    },
    changePlayIcon(){
        $('[data-card-icon-play]').show();
        $('[data-card-icon-pause]').hide();
        if (window.audio.paused) {
            $('[data-icon-play]').removeAttr('hidden').show();
            $('[data-icon-pause]').hide();
            $('[data-play-episode="'+ this.episodeId +'"] [data-card-icon-play]').show();
            $('[data-play-episode="'+ this.episodeId +'"] [data-card-icon-pause]').hide();
        } else {
            $('[data-icon-play]').hide();
            $('[data-icon-pause]').removeAttr('hidden').show();
            $('[data-play-episode="'+ this.episodeId +'"] [data-card-icon-play]').hide();
            $('[data-play-episode="'+ this.episodeId +'"] [data-card-icon-pause]').removeAttr('hidden').show();

        }
    },
    updateProgressBar(e) {
        const progress = (window.audio.currentTime / window.audio.duration) * 100;
        if (isNaN(progress))
            return;
        $('[data-audio-progress]').val(progress);
        const minutes = Math.floor(window.audio.currentTime / 60);
        const seconds = Math.floor(window.audio.currentTime % 60);
        let playerType = $('[data-player]').attr('data-player');
        $('[data-audio-current-time]').text(String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0'));
        $('#start-time').val(window.audio.currentTime);
        // Save watch time every 5 seconds after the initial 10 seconds
        if (playerType === 'podcast' && window.audio.currentTime >= 10) {
            if (!player.lastSavedTime || window.audio.currentTime - player.lastSavedTime >= 5) {
                player.saveWatchTime();
                player.lastSavedTime = window.audio.currentTime;
            }
        }

        if (playerType === 'radio' && (!player.lastRadioInfoTime || window.audio.currentTime - player.lastRadioInfoTime >= 5)){
            player.getStationInfo();
            player.lastRadioInfoTime = window.audio.currentTime;
        }


        if (e.type === 'ended'){
            if ($('[data-player]').attr('data-player') === 'podcast'){
                player.setListened(player.episodeId);
                player.saveWatchTime(true);
            }

            if ($('[data-player-autoplay]').prop('checked')){
                player.changeTrack('next')
            }
            player.changePlayIcon();

        }
    },
    getStationInfo(){
        let self = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '/get-station-info/'+self.stationId,
            success: function (response) {
                $('[data-artist]').html(response.artist);
                $('[data-song]').html(response.song);
            }
        })
    },
    changePlaybackRate(forceRate = null){
        if (forceRate !== null) {
            window.audio.playbackRate = forceRate;
            $('[data-playback-rate]').text(Number(forceRate).toFixed(1));
            return;
        }
        let currentPlaybackRate = window.audio.playbackRate;
        let newPlaybackRate = (currentPlaybackRate + this.playbackRateStep > this.playbackRateMax)
            ? 1
            : currentPlaybackRate + this.playbackRateStep;
        newPlaybackRate = Math.round(newPlaybackRate * 100) / 100;
        this.playbackRate = newPlaybackRate;
        window.audio.playbackRate = this.playbackRate;
        $('[data-playback-rate]').text(Number(newPlaybackRate).toFixed(1));
    },
    rewind(direction, secondsStep = 10){
        let newTime = window.audio.currentTime + (secondsStep * (direction === 'forward' ? 1 : -1));

        if (newTime < 0) {
            newTime = 0; // Set the new time to 0 if rewinding before the start
        } else if (newTime > window.audio.duration) {
            newTime = window.audio.duration; // Set the new time to the duration if rewinding beyond the end
        }

        window.audio.currentTime = newTime;
    },
    markAsListened() { //???
        window.audio.currentTime = window.audio.duration;
        player.saveWatchTime(true);
        if ($('[data-player-autoplay]').prop('checked')){
            player.changeTrack('next');
        }
    },
    changeTrack(direction){
        let self = this;
        let parentContainer = $('[data-player-playlist]');
        let currentElement = parentContainer.find('[data-player-playlist-item="'+ self.episodeId +'"]');
        let nextElement = null;
        if (direction === 'next'){
            if (currentElement.next('[data-player-playlist-item]').length){
                nextElement = currentElement.next('[data-player-playlist-item]');
            }
            else if (parentContainer.find('[data-player-playlist-item]:first').length){
                nextElement = parentContainer.find('[data-player-playlist-item]:first');
            }
        }
        else if (direction === 'prev') {
            if (currentElement.prev('[data-player-playlist-item]').length){
                nextElement = currentElement.prev('[data-player-playlist-item]');
            }
            else if (parentContainer.find('[data-player-playlist-item]:last').length){
                nextElement = parentContainer.find('[data-player-playlist-item]:last');
            }
        }
        if (nextElement)
            self.changeEpisode(nextElement.data('player-playlist-item'));

    },
    savePlaylistSorting(){
        let self = this;
        let playlist = [];
        $('[data-player-playlist-item]').each(function () {
            playlist.push($(this).data('player-playlist-item'));
        });
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/save-playlist-sorting',
            data: {
                playlist: playlist
            },
            success: function (response) {
                if (response.status === 'success') {
                    //$('[data-player-playlist]').html(response.html);
                }
            }
        })
    },
    setSource(src){
        let paused = false;
        if (window.audio.paused) {
            paused = true;
        }
        if (!paused) {
            window.audio.pause();
            this.changePlayIcon();
        }
        window.audio.setAttribute("src", src);
        if (!paused) {
            window.audio.play();
            this.changePlayIcon();
        }
    },
    addToPlaylist(id) {
        let self = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/add-to-playlist/' + id,
            success: function (response) {
                if (response.status === 'success') {
                    $('[data-player-playlist]').html(response.html);
                    $('[data-add-to-playlist]').removeClass('active');
                    response.episodes.forEach(function (episodeId) {
                        $('[data-add-to-playlist="'+ episodeId +'"]').addClass('active');
                    })
                }
            }
        })
    },
    playlistSort(episodeId, direction) {
        let parentContainer = $('[data-player-playlist]');
        let currentElement = $('[data-player-playlist-item="'+ episodeId +'"]');
        let currentIndex = parentContainer.children('[data-player-playlist-item]').index(currentElement);
        let playlistItemsCount = parentContainer.children('[data-player-playlist-item]').length;

        let targetIndex;
        if (direction === 'up') {
            targetIndex = currentIndex - 1;
            if (targetIndex < 0) {
                targetIndex = playlistItemsCount - 1;
            }
        } else if (direction === 'down') {
            targetIndex = currentIndex + 1;
            if (targetIndex >= playlistItemsCount) {
                targetIndex = 0;
            }
        }
        let targetElement = parentContainer.children('[data-player-playlist-item]').eq(targetIndex);

        if (direction === 'up') {
            if (targetIndex === 0) {
                targetElement.before(currentElement);
            } else {
                if (targetIndex === playlistItemsCount - 1) {
                    currentElement.insertAfter(targetElement);
                }
                else
                    currentElement.insertBefore(targetElement);
            }
        } else {
            if (targetIndex === 0) {
                currentElement.insertBefore(targetElement);
            }
            else
                currentElement.insertAfter(targetElement);
        }
        this.savePlaylistSorting();
    },
    adjustVolume(e) {
        const volumeSlider = $('[data-volume-widget]');
        const offset = volumeSlider.offset();
        const sliderHeight = volumeSlider.height();
        let newY = e.pageY - offset.top;
        newY = Math.max(newY, 0);
        newY = Math.min(newY, sliderHeight);
        const volumePercent = 100 - (newY / sliderHeight * 100);
        $('[data-volume-progress]').height(volumePercent + '%');
        let volume = volumePercent / 100;
        this.volume = volume;
        this.setVolume(volume)
    },
    setVolume(value) {
        window.audio.volume = value;
    },
    /**
     *
     * @param direction string up|down
     * @param entity string hours|minutes
     */
    timerChangeTime(direction, entity, step = 1){
        if (entity === 'hours'){
            if (direction === 'up') {
                this.timer.hours += step;
                if (this.timer.hours >= 24) this.timer.hours = 0;
            } else if (direction === 'down') {
                this.timer.hours -= step;
                if (this.timer.hours < 0) this.timer.hours = 23;
            }
        }
        else if (entity === 'minutes'){
            if (direction === 'up') {
                this.timer.minutes += step;
                if (this.timer.minutes >= 60) {
                    this.timer.minutes = 0;
                    this.timerChangeTime('up', 'hours');
                }
            } else if (direction === 'down') {
                this.timer.minutes -= step;
                if (this.timer.minutes < 0) {
                    this.timer.minutes = 60 - step;
                    this.timerChangeTime('down', 'hours');
                }
            }
        }
        this.timerRenderTimes();
        //console.log(`Timer is now ${this.timer.hours}:${this.timer.minutes}`);
    },
    timerRenderTimes(){
        $('[data-timer-hours]').text(String(this.timer.hours).padStart(2, '0'));
        $('[data-timer-minutes]').text(String(this.timer.minutes).padStart(2, '0'));
    },

    initVolumeSlider(){
        let self = this;
        $(document).ready(function() {
            let isMouseDown = false;
            $(document).on('mousedown', '[data-volume-button]', function(){
                isMouseDown = true;
            })

            $(document).mouseup(function() {
                isMouseDown = false;
            });
            $(document).on('click', '[data-volume-track]', function(e){
                self.adjustVolume(e);
            })

            $(document).mousemove(function(e) {
                if (isMouseDown) {
                    self.adjustVolume(e);
                }
            });
        });
    },
    initEvents(){
        let self = this;
        $(document).on('click', '[data-play-station]', function(){
            self.changeStation($(this).data('play-station'));
        });
        $(document).on('click', '[data-play-episode]', function(){
            self.changeEpisode($(this).data('play-episode'));
        });
        $(document).on('click', '[data-play-button]', function(){
            self.play();
        })
        $(document).on('click', '[data-np-modal-timer-trigger]', function(){
            self.timerShow();
        });
        $(document).on('click', '[data-np-modal-timer-close]', function(){
            self.timerClose();
        });
        $(document).on('click', '[data-timer-reset]', function(){
            self.timerReset();
        });
        $(document).on('click', '[data-timer-apply]', function(){
            self.timerApply();
        });
        $(document).on('click', '[data-change-source]', function(){
            let src = '';
            if (self.sourceType === 'sd') {
                src = $('#audio-source-hd').val();
                if (src.length > 7) {
                    self.sourceType = 'hd';
                    $(this).addClass('active');
                    self.setSource(src);
                }
            } else if (self.sourceType === 'hd') {
                src = $('#audio-source').val();
                if (src.length > 7) {
                    self.sourceType = 'sd';
                    $(this).removeClass('active');
                    self.setSource(src);
                }
            }

        });

        $(document).on('click', '[data-timer-change-hours]', function(){
            self.timerChangeTime($(this).data('timer-change-hours'), 'hours', self.timer.hoursStep);
        });
        $(document).on('click', '[data-timer-change-minutes]', function(){
            self.timerChangeTime($(this).data('timer-change-minutes'), 'minutes', self.timer.minutesStep);
        });
        $(document).on('change', '[data-audio-progress]', function(){
            window.audio.currentTime = window.audio.duration / 100 * $(this).val();
            self.saveWatchTime();
        });
        $(document).on('click', '[data-rewind]', function(){
            let secondsStep = 10;
            let direction = $(this).data('rewind');
            self.rewind(direction, secondsStep);
        });
        $(document).on('click', '[data-change-track]', function(){
            let direction = $(this).data('change-track');
            self.changeTrack(direction);
        })
        $(document).on('click', '[data-playback-rate]', function(){
            self.changePlaybackRate();
        });
        $(document).on('click', '[data-add-to-playlist]', function(){
            let id = $(this).data('add-to-playlist');
            self.addToPlaylist(id);
            return false;
        });
        $(document).on('change', '[data-player-autoplay]', function(){
            self.autoplay = $(this).prop('checked');
        });
        $(document).on('click', '[data-playlist-sort]', function(){
            let episodeId = $(this).parents('[data-player-playlist-item]').data('player-playlist-item');
            let direction = $(this).data('playlist-sort');
            self.playlistSort(episodeId, direction);
        })
        $(document).on('click', '[data-np-trigger]', function(){
            $('[data-player]').addClass('open');
            self.playerOpenedMob = true;
        })
        $(document).on('click', '[data-np-modal-close]', function(){
            $('[data-player]').removeClass('open')
            self.playerOpenedMob = false;
        })
        $(document).on('click', '[data-set-listened]', function(){
            let episodeId = $(this).attr('data-set-listened')
            self.setListened(episodeId);
        })
    },
}
