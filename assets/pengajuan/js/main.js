import Contract from "./contract_field";

$(function () {
    const $pengajuan_layanan = $('select#Pengajuan_layanan');
    const $contract_number = $('input#Pengajuan_contract_layanan');
    // const $pengajuan_persyaratan = $('textarea#Pengajuan_persyaratan');
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ptspId = urlParams.get('filters[ptsp_id][value]');
    const _token = $('input#Pengajuan__token');
    const $pengajuan_contract = $('input#Pengajuan_contract_date');
    const $pengajuan_count = $('input#Pengajuan_contract_count');

    const contract = new Contract();

    contract.getLastContract($pengajuan_contract.val(), _token.val(), function (e) {
        let total = e + 1;
        $pengajuan_count.val(total.toString().padStart(3, "0"));
    });

    contract.onChange($pengajuan_layanan, (value) => {
        $contract_number.val(value)

        $.ajax({
            url: '/api/persyaratan',
            method: 'GET',
            data: {
              ptsp: ptspId,
              layanan: value
            },
            contentType : 'application/json',
            dataType : 'json',
            headers: {
                auth_token: _token.val()
            },
            success: function (e) {
                $('div.content p.text').html(e?.persyaratan)

                const arr = [];
                e?.refLayananAttachments.forEach(function (value, key) {
                    const link = `<li><a href="/uploads/files/${value.files}" target="_blank"> ${value.files} </a></li>`;
                    arr.push(link);
                })

                $('div.layanan_attacments').html(
                    `<ul >${arr.join('')}</ul>`
                )
            }
        })



    }).then(r => console.log(r));
})
