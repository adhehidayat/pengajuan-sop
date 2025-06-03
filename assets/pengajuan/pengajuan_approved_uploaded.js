$(function () {
    const field$ = $('#Pengajuan_pengajuanProgress_status');
    const fieldUpload = $('.approved_uploaded')

    function checked() {
        const selectedId = $('input[name="Pengajuan[pengajuanProgress_status]"]:checked').attr('id');
        const label = $('label[for="' + selectedId + '"]').text().trim();

        fieldUpload.hide()

        if (label === "Diterima") {
            fieldUpload.show()
        } else {
            fieldUpload.hide()
        }
    }

    checked()

    field$.on('change', function () {
        checked();
    })
})