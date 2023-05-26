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
    });

    $(document).on('click', '.listen-later', function () {
        let id = $(this).attr('data-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/queue-episode',
            data: {id: id},
            success: function(response) {

            }
        })
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
        })
    });
}
