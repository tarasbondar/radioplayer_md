export function nowPlaying() {
    const
        player = document.querySelector('[data-player]'),
        body = document.querySelector('body'),
        nowPlayingEl = document.querySelector('[data-now-playing]'),
        npModal = document.querySelector('[data-np-modal]'),
        npModalPlayer = document.querySelector('[data-np-modal-player]'),
        npModalTrigger = document.querySelector('[data-np-trigger]'),
        npModalClose = document.querySelectorAll('[data-np-modal-close]'),
        npModalTimerTrigger = document.querySelector('[data-np-modal-timer-trigger]'),
        npModalTimer = document.querySelector('[data-np-modal-timer]'),
        npModalTimerClose = document.querySelector('[data-np-modal-timer-close]'),
	    npModalPlayingTrigger = document.querySelector('[data-np-modal-playing-trigger]'),
	    npModalPlaying = document.querySelector('[data-np-modal-playing]'),
	    npModalPlayingClose = document.querySelector('[data-np-modal-playing-close]'),
		npModalPlayingListTrigger = document.querySelector('[data-np-modal-playing-list-trigger]'),
	    npModalPlayingList = document.querySelector('[data-np-modal-playing-list]'),
	    npModalPlayingListClose = document.querySelector('[data-np-modal-playing-list-close]');

    if (npModalTrigger) {
        npModalTrigger.addEventListener('click', function(){
            player.classList.add('open');
            body.classList.add('modal-open');
        });
    }

    npModalClose.forEach(function(e){
        e.addEventListener('click', function(){
            player.classList.remove('open');
            body.classList.remove('modal-open');
            npModalTimer.classList.remove('open');
            npModalPlayer.classList.remove('hidden');
        })
    });

    if (npModalTimerTrigger) {
        npModalTimerTrigger.addEventListener('click', function(){
            if (npModalPlayer) {
                npModalPlayer.classList.add('hidden');
            } else if (npModalPlaying) {
                npModalPlaying.classList.add('hidden');
            }
            npModalTimer.classList.add('open');
        });
    }

    if (npModalTimerClose) {
        npModalTimerClose.addEventListener('click', function(){
            npModalTimer.classList.remove('open');
            if (npModalPlayer) {
                npModalPlayer.classList.remove('hidden');
            } else if (npModalPlaying) {
                npModalPlaying.classList.remove('hidden');
            }
        });
    }



    // if (npModalPlayingListClose !== null)
    //     npModalPlayingListClose.addEventListener('click', function(){
    //         npModalPlayingList.classList.remove('open');
    //         npModalPlaying.classList.remove('hidden');
    //     });

}
