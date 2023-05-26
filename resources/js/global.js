export function global() {
    $(document).on('click', '.download-episode', function(){
        let id = $(this).attr('data-id');
        console.log(id);
        /*$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/download-episode',
            data: {id: id},
            success: function(response) {

            }
        })*/

        return false;
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
