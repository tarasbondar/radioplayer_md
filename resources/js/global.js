import {player} from "./player";
import { Tooltip } from "bootstrap";

export function global() {
    $(document).on('click', '.download-episode', function(){
        let elem = $(this);
        let id = elem.attr('data-id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                responseType: "blob",
            },
            method: 'GET',
            url: '/download-episode',
            data: {id: id},
            success: function(response) {
                let blob = new Blob([response]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/get-download-data',
                    data: {id: id},
                    success: function (filename) {
                        elem.closest('.podcast__elem').find('.download-file').addClass('active');
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();
                    }
                })

            }
        });
    });

    $(document).on('click', '[data-listen-later]', function () {
        let id = $(this).attr('data-listen-later');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/queue-episode',
            data: {id: id},
            success: function(response) {
                $('[data-listen-later]').removeClass('active');
                response.episodes.forEach(function (episodeId) {
                    $('[data-listen-later="'+ episodeId +'"]').addClass('active');
                })
            }
        });
        return false;
    });

    $(document).on('click', '.delete-episode', function () {
        let id = $(this).attr('data-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'DELETE',
            url: '/delete-episode',
            data: {id: id},
            success: function (response) {

            }
        });
    });

    $(document).on('click', '.shareButton', function () {
        let fullHostname = window.location.href;
        if ($('#player-radio').length > 0) {
            let protocol = window.location.protocol;
            let hostname = window.location.hostname;
            fullHostname = protocol + '//' + hostname + '?station=' + $('.fav-station').attr('value');
        }
        if (navigator.share) {
            navigator.share({
                title: $('.np-modal__playing .np-modal__player-body__header__title').text(),
                url: fullHostname
            })
              .then(() => console.log('Successful share'))
              .catch((error) => console.log('Error sharing:', error));
        } else {
            copyToClipboard(fullHostname);
            const tooltip = Tooltip.getInstance('.shareButton');
            tooltip.show();
            setTimeout(() => {
                tooltip.hide();
            }, 1000);
        }
    });

    $( document ).ready(function() {
        var queryString = window.location.search;
        var searchParams = new URLSearchParams(queryString);
        var stationValue = searchParams.get('station');
        if (stationValue !== null) {
            player.init();
            player.changeStation(stationValue);
        }
    });
}

function copyToClipboard(text) {
    var tempInput = document.createElement("textarea");
    tempInput.style.position = "fixed";
    tempInput.style.opacity = 0;
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
}
