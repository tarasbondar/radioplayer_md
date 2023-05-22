export let playerRadio = {
    volume: 0.5,
    init(){
        this.initVolumeSlider();
        this.initClickEvents();
    },
    createPlayer(){
        if (typeof window.audio === 'undefined')
            window.audio = document.createElement("audio");
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
    initClickEvents(){
        let self = this;
        $(document).on('click', '[data-play-station]', function(){
            self.changeStation($(this).data('play-station'));
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
    },
    timerShow(){
        $('[data-np-modal-player]').addClass('hidden');
        $('[data-np-modal-timer]').removeClass('hidden');
        $('[data-np-modal-timer]').addClass('open');
    },
    timerClose(){
        $('[data-np-modal-player]').removeClass('hidden');
        $('[data-np-modal-timer]').addClass('hidden');
        $('[data-np-modal-timer]').removeClass('open');
    },
    timerReset(){
        // implementation
        this.timerClose();
    },
    timerApply(){
        // implementation
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
                //if (audio.canPlayType("audio/mpeg")) {
                self.setSource($('#audio-source').val());
                //}
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
    setSource(src){
        window.audio.setAttribute("src", src);
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
    }
}
