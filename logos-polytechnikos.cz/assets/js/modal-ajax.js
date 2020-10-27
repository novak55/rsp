function openForm(url){
    $('#novyFormlModal').modal('show');
    $('.modal-content').replaceAll('');
    $('.modal-content').html('            <div class="modal-header">\n' +
        '                <div class="row"><div class="col-sm-11"><h4 class="modal-title">Načítám data formuláře.</h4></div>' +
        '                <div class="col-sm-1"><button type="button" class="close" data-dismiss="modal">&times;</button></div></div>' +
        '            </div>' +
        '            <div class="modal-body">' +
        '               <div class="text-center"><img src="/images/loader.gif" style="width: 20px;"></div>' +
        '            </div>'+
        '            <div class="modal-footer">' +
        '                <div class="row">' +
        '                </div>' +
        '            </div>');
    $.ajax({
        type:'GET',
        url: url,
        success: function(html){
            $('.modal-content').html(html)
        },
        error: function (html) {
            $('.modal-content').replaceAll('');
            $('.modal-content').html(' <div class="modal-header"><strong>Došlo k neočekávané události.</strong><button type="button" class="close " data-dismiss="modal">×</button></div>');
        }
    });

}

$('.open-form').click(function () {
    openForm($(this).attr('data-url'));
});

$(function(){
    $('.open-form').click(function () {
        openForm($(this).attr('data-url'));
    })
})
