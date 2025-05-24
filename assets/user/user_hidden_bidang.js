import './user_hidden_bidang.css'
$(function () {
    const bidangWrapper = $('#User_ptsp').closest('.col-12');

    // Fungsi untuk menampilkan/sembunyikan select Bidang
    function toggleBidangSelect() {
        const selectedRoles = $('#User_roles').val() || [];

        if (selectedRoles.includes('ROLE_OPERATOR_BIDANG')) {
            bidangWrapper.show();
        } else {
            bidangWrapper.hide();
            $('#User_ptsp').val(''); // reset juga kalau mau
        }
    }

    // Event ketika role berubah
    $('#User_roles').on('change', function () {
        toggleBidangSelect();
    });

    // Inisialisasi awal saat halaman
    //
    // load
    toggleBidangSelect();

})