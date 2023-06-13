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
        if (confirm('Are you sure?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'DELETE',
                url: '/delete-episode',
                data: {id: id},
                success: function (response) {
                    window.location.href = '/podcasts/' + response + '/view';
                }
            });
        }
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

    $(document).on('click', '.share-episode', function() {
        let id = $(this).val();
        let protocol = window.location.protocol;
        let hostname = window.location.hostname;
        let share = protocol + '//' + hostname + '/episodes/' + id + '/view';
        copyToClipboard(share);
    });

    $(document).on('click', '.subscribe-to', function () {
        let button = $(this);
        let classes = button.attr('class').split(" ");
        let podcast_id = 0;
        $.each(classes, function (k, v) {
            if (v.search('podcast-') >= 0) {
                podcast_id = v.split("-")[1];
            }
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/subscribe-to',
            data: {id: podcast_id },
            success: function(response){
                $('.button-container').html(response);
            }
        })
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

    $(document).on('click', '#search-all', function () {
        $('.button-category.active').removeClass('active');
        $('.button-tag.active').removeClass('active');
        $(this).add('active');
        stations_filters();
    });

    $(document).on('click', '.button-category:not(.active)', function() {
        $('.button-category').removeClass('active');
        $('#search-all').removeClass('active');
        $(this).addClass('active');
        stations_filters();
    });

    $(document).on('click', '.button-tag:not(.active)', function() {
        $('.button-tag').removeClass('active');
        $(this).addClass('active');
        stations_filters();
    });

    function stations_filters() {

        let category_id = $('.button-category.active').val();

        let tag_id = $('.button-tag.active').val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/update-stations',
            data: {'category_id': category_id, 'tag_id': tag_id},
            success: function(response) {
                $('.stations-container').html(response);
            }
        });

    }

    $(document).on('click', '.fav-station', function(){
        let station_id = $(this).val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '/favorite-station/' + station_id,
            success: function(response) {
                if (response.action === 'added') {
                    if ($('.favorites-container .swiper-slide').length === 0) {
                        $('.no-favorite').addClass('d-none');
                        $('.category-slider__wrapper').removeClass('d-none');
                        $('.favorites-container').html(response.output);
                    } else {
                        $('.swiper-wrapper').append(response.output);
                    }
                    $('.fav-station[value="'+response.id+'"]').addClass('active');
                }
                if (response.action === 'deleted') {
                    $('.fav-station[value="'+response.id+'"]').removeClass('active');
                    $('.favorites-container > .station-' + response.id).remove();
                    if ($('.favorites-container .swiper-slide').length === 0) {
                        $('.category-slider__wrapper').addClass('d-none');
                        $('.no-favorite').removeClass('d-none');
                    }
                }
            }
        })
    });

    $(document).on('click', '[data-np-modal-playing-list-trigger]', function(){
        $('[data-np-modal-player]').addClass('hidden')
        $('[data-np-modal-playing-list]').addClass('open');
    })

    $(document).on('click', '[data-np-modal-playing-list-close]', function(){
        $('[data-np-modal-player]').removeClass('hidden')
        $('[data-np-modal-playing-list]').removeClass('open');
    })

    $(document).on('keyup', '#search-field-all', function() {
        allSearch();
    });

    $(document).on('change', '.input__checkbox-all', function () {
        allSearch();
    });

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            if ($('#allsearch-page').val() < $('#allsearch-last').val()) { //last page check
                appendEpisodes();
            }
        }
    });

    function allSearch() {
        let categories = [];
        $('.input__checkbox-all:checked').each(function(){
            categories.push($(this).val());
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/all-search',
            data: {
                text: $('#search-field-all').val(),
                categories: categories.join(','),
                author: ''
            },
            success: function(response) {
                $('.podcasts-container').html(response.podcasts);
                $('.episodes-container').html(response.episodes);
                $('#allsearch-page').value = 1;
                $('#allsearch-last').val(response.page_count)
            }
        })

    }

    $(document).on('keyup', '#search-field-my', function() {
        if ($(this).val().length > 2 || $(this).val().length === 0) {
            mySearch();
        }
    });

    $(document).on('change', '.input__checkbox-my', function () {
        mySearch();
    });

    function mySearch() {
        let categories = [];
        $('.input__checkbox-my:checked').each(function(){
            categories.push($(this).val());
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/my-podcasts-search',
            data: {
                text: $('#search-field-my').val(),
                categories: categories.join(','),
            },
            success: function(response) {
                $('.podcasts-container').html(response);
            }
        })

    }

    function appendEpisodes() {
        let categories = [];
        $('.input__checkbox:checked').each(function(){
            categories.push($(this).val());
        });
        let page = $('#allsearch-page').val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/append-episodes',
            data: {
                text: $('#search-field').val(),
                categories: categories.join(','),
                page: page,
            },
            success: function(response) {
                $('.episodes-container').append(response);
                $('#allsearch-page').val(++page);
            }
        })

    }


    $(document).on('change', '[data-episode-form] input[name="source"]', function(){
        let $form = $('[data-episode-form]');
        let selectedFile = this.files[0];
        let fileName = selectedFile.name;
        let fileSizeBytes = selectedFile.size;
        let sizeFormatted = core.formatBytes(fileSizeBytes, 2);
        $form.find('[data-item-filename]').html(fileName);
        $form.find('[data-item-uploaded]').html(sizeFormatted);
        $form.find('[data-item-size]').html(sizeFormatted);
        $form.find('[data-file-item]').removeClass('hidden');
    });
    $(document).on('click', '[data-file-remove]', function(){
        let $form = $('[data-episode-form]');
        $('[data-file-list] [data-file-item]').addClass('hidden');
        $form.find('input[name="source"]').val('');
        $form.find('input[name="file_remove"]').val(1);
    });

    $(document).on('click', '.categories-checkbox > input', function () {
        let categories_ids = [];
        let categories_keys = [];

        $('.categories-check:checkbox:checked').each(function () {
            let val = $(this).val().split("-");
            categories_ids.push(val[0]);
            categories_keys.push(val[1]);
        });

        $('#categories-ids').val(categories_ids.join(','));
        $('#categories-keys').val(categories_keys.join(', '));
    });

    $(document).on('click', '#delete-podcast', function () {
        if (confirm('Are you sure?')) {
            let id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'DELETE',
                url: '/delete-podcast',
                data: {id: id},
                success: function () {
                    window.location.href = '/my-podcasts';
                }
            })
        }

    });

    $(document).on('click', '#clear-history', function () {
        let link = $(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/clear-history',
            success: function () {
                $('.podcast__list').html('');
                link.hide();
            }
        })
    });
    $(document).on('click', '#clear-downloads', function () {
        let link = $(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/clear-downloads',
            success: function () {
                $('.podcast__list').html('');
                link.hide();
            }
        })
    });

    $(document).on('click', '#more', function(){
        $('#descr-full').removeAttr('hidden').show();
        $('#descr-short').hide();
    });

    $(document).on('click', '#less', function(){
        $('#descr-short').removeAttr('hidden').show();
        $('#descr-full').hide();
    });
    $(document).on('click', '.podcast__back, .podcast__episode-back', function(){
        history.back();
    });

    $(document).on('change', '#search-name', function() {
        searchPodcast();
    });

    $(document).on('change', '.input__checkbox-podcasts', function () {
        searchPodcast();
    });

    function searchPodcast() {
        let categories = [];
        $('.input__checkbox-podcasts:checked').each(function(){
            categories.push($(this).val());
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/update-podcasts',
            data: {
                name: $('#search-name').val(),
                categories: categories.join(','),
                author: ($('#author-id').length) ? $('#author-id').val() : ''
            },
            success: function(response) {
                $('.podcasts-container').html(response);
            }
        })
    }

    $(document).on('submit', '[data-ajax-form]', function(){
        let form = $(this);
        let url = form.attr('action');
        let method = form.attr('method');
        let data = form.serialize();
        $('[data-for]').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: method,
            url: url,
            data: data,
            success: function (response) {
                if (response.status === 'success') {
                    if (typeof response.redirectUrl != 'undefined') {
                        window.location.href = response.redirectUrl;
                    }
                } else {
                    $.each(response.errors, function (key, value) {
                        form.find('[data-for="' + key + '"]').html(value[0]);
                    });
                }
            }
        });
        return false;
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
