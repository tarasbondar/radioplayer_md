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
        $('[data-np-modal-timer]').removeClass('hidden');
        $('[data-np-modal-timer]').addClass('open');


        this.timer.hours = this.timer.intervalHours;
        if (this.timer.intervalMinutes)
            this.timer.minutes = this.timer.intervalMinutes + 1;
        this.timerRenderTimes();
    },
    timerClose(){
        $('[data-np-modal-player]').removeClass('hidden');
        $('[data-np-modal-timer]').addClass('hidden');
        $('[data-np-modal-timer]').removeClass('open');
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
            }
            //console.log(this.timer.seconds)
        }, 1000);

        this.timerClose();
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
                $('.body').append(response).addClass('player-open');
                //if isset then stop
                self.createPlayer();
                self.setSource($('#audio-source').val());
                if (self.timer.isActive)
                    self.timerApply(true);
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
                $('.body').append(response).addClass('player-open');
                self.createPlayer();
                self.setSource($('#audio-source').val());
                if (self.timer.isActive)
                    self.timerApply(true);
                self.changePlaybackRate(self.playbackRate);
            }
        })
    },
    play(){
        if (window.audio.paused) {
            this.setVolume(this.volume);
            window.audio.play();
            $('.player-play').hide();
            $('.player-pause').removeAttr('hidden').show();
        } else {
            window.audio.pause();
            $('.player-play').show();
            $('.player-pause').hide();
        }
    },
    updateProgressBar() {
        const progress = (window.audio.currentTime / window.audio.duration) * 100;
        if (isNaN(progress))
            return;
        $('[data-audio-progress]').val(progress);
        const minutes = Math.floor(window.audio.currentTime / 60);
        const seconds = Math.floor(window.audio.currentTime % 60);

        $('[data-audio-current-time]').text(String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0'));
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
    setSource(src){
        let paused = false;
        if (window.audio.paused) {
            paused = true;
        }
        if (!paused) {
            window.audio.pause();
        }
        window.audio.setAttribute("src", src);
        if (!paused) {
            window.audio.play();
        }
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
        });
        $(document).on('click', '[data-rewind]', function(){
            let secondsStep = 10;
            let direction = $(this).data('rewind');
            self.rewind(direction, secondsStep);
        })
        $(document).on('click', '[data-playback-rate]', function(){
            self.changePlaybackRate();
        });
    },
}
