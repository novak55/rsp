$(function () {
    $('.confirm').click(function (event) {
        event.preventDefault();
        let a = $(this);
        let confirmModal = $('#confirmModal');
        confirmModal.find('#confirmModalTitle').text(a.data('confirmTitle'));
        if( a.data('confirmUrl') !== undefined ){
            $('#confirmModalText').html('<div class="text-center"><img src="/images/load.gif" style="width: 20px;"></div>');
            $('#confirmModal form').attr('action', a.data('confirmUrl'));
            $.ajax({
                type: 'GET',
                url: a.data('confirmUrl'),
                success: function (html) {
                    $('#confirmModalText').html(html)
                },
                error: function (html) {
                    $('#confirmModalText').replaceAll('');
                    $('#confirmModalText').html(' <div class="modal-header"><strong>Došlo k neočekávané události.</strong>');
                    confirmModal.find('#modalFooterButtons').html('<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Zavřít</button>');
                }
            });
        }else {
            confirmModal.find('#confirmModalText').html(a.data('confirmText'));
        }
        if(a.data('confirmButtons') !== undefined){
            confirmModal.find('#modalFooterButtons').html(a.data('confirmButtons'));
        }
        confirmModal.modal("show");
        confirmModal.find('#confirmModalSave').click(function () {
            location.replace(a.attr('href'));
        })
    })
});