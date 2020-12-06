$('.confirm').click(function (event) {
    event.preventDefault();
    let a = $(this);
    let confirmModal = $('#confirmModal');
    confirmModal.find('#confirmModalTitle').text(a.data('confirmTitle'));
    if(a.hasClass('confirm-xl')){confirmModal.find('.modal-dialog').addClass('modal-xl');}
    if(a.hasClass('confirm-lg')){confirmModal.find('.modal-dialog').addClass('modal-lg');}
    if( a.data('confirmUrl') !== undefined ){
        $('#confirmModalText').html('<div class="text-center"><img src="/images/loader.gif" style="width: 20px;"></div>');
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
    let buttons = confirmModal.find('#modalFooterButtons').html();
    if(a.data('confirmButtons') !== undefined){
        confirmModal.find('#modalFooterButtons').html(a.data('confirmButtons'));
    }
    confirmModal.modal("show");
    confirmModal.find('#confirmModalSave').click(function () {
        location.replace(a.attr('href'));
    })
    $("#confirmModal").on('hidden.bs.modal', function () {
        if(a.hasClass('confirm-xl')){confirmModal.find('.modal-dialog').removeClass('modal-xl');}
        if(a.hasClass('confirm-lg')){confirmModal.find('.modal-dialog').removeClass('modal-lg');}
        confirmModal.find('#modalFooterButtons').html(buttons);
    });
});
