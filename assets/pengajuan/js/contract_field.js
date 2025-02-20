export default class Contract {

    onChange(field, callback) {
        return new Promise((resolve) => {
            $(field).on('change', (elm) => {
                const result = this.onProcess(elm);
                callback(result);
            })
        })
    }

    onProcess(elm) {
        let value = elm.target.tomselect.items[0]

        if (typeof value === 'undefined') {
            value = 'xx'
        } else {
            value = value.padStart(2, '0');
        }

        return value;
    }

    getLastContract(contract, token, callback) {
        $.ajax({
            url: '/api/contract',
            method: "GET",
            data: {
                contract: contract
            },
            headers: {
                auth_token: token
            },
            success: function (e) {
                callback(e)
            }
        })
    }
}



// $(function () {
//     // function layanan
//     const fields = $('select#Pengajuan_layanan');
//
//     fields.on('change', function (e) {
//         let value = e.target.tomselect.items[0];
//
//         if (typeof value === 'undefined') {
//             value = "xx";
//         } else {
//             value = value.padStart(2, "0");
//         }
//
//         $('input#Pengajuan_contract_layanan').val(value)
//
//     })
// })
