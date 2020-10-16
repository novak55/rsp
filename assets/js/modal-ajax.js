$(function(){
    $('.open-form').click(function (event) {
        event.preventDefault();
        let a = $(this);
        let url = a.attr('href');
        if( a.data('url') !== undefined ){
            url = a.data('url');
        }
        $('#novyFormlModal').modal('show');
        $('.modal-content').replaceAll('');
        $('.modal-content').html('            <div class="modal-header">\n' +
            '                <h4 class="modal-title">Načítám data formuláře.</h4>' +
            '                <button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '            </div>' +
            '            <div class="modal-body d-flex justify-content-center">' +
            '                <span class="loader">  </span>' +
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
    })
})