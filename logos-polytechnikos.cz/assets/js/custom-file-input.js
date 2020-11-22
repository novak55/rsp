$(function () {
    $('.custom-file input').attr('placeholder', 'Soubor nevybrán').next('.custom-file-label').html('Soubor nevybrán');
});

$('.custom-file input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    if (fileName.length > 0) {
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    }
    else {
        $(this).next('.custom-file-label').removeClass("selected").html('Soubor nevybrán');
    }
});
