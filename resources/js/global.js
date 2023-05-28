export function global() {
    $(document).on('click', '.download-episode', function(){
        let id = $(this).attr('data-id');

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

    //delete-episode/$episode['id']
    $(document).on('click', '.delete-episode', function () {
        let id = $(this).attr('data-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/delete-episode',
            data: {id: id},
            success: function (response) {

            }
        });
        return false;
    });
}
