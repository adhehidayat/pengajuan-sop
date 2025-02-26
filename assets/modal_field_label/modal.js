$(function() {
    // $('div#modalLabel').on('show.bs.modal', function (event) {
    //     const button = $(event.relatedTarget);
    //     const url = button.data('url');
    //     console.log('test')
    //

    // })

    $('a#modalLabelBtn').on('click', function(event) {
        const url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                $('#modalLabel .modal-body').html(response)
            }
        })
    })
})


