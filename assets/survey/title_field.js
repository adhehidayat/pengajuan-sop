$(function () {
    const field$ = $("select#Survey_survey");

    field$.on('change', function () {
        $('#Survey_title').val($("#Survey_survey option:selected").text());
    })
})