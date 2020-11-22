function openForm(url){
    if (url !== undefined) {
        let novyFormlModal = $('#novyFormlModal');
        novyFormlModal.modal('show');
        let modalContent = novyFormlModal.find('.modal-content');
        modalContent.replaceAll('');
        modalContent.html('<div class="modal-header"><h4 class="modal-title">Načítám data formuláře.</h4>' +
            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '</div>' +
            '            <div class="modal-body">' +
            '               <div class="text-center"><img src="/Img/load.gif" style="width: 50px;"></div>' +
            '            </div>' +
            '            <div class="modal-footer">' +
            '                <div class="row">' +
            '                </div>' +
            '            </div>');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (html) {
                modalContent.html(html);
                modalContent.find('.open-form').click(function () {
                    openForm($(this).attr('data-url'));
                })
                window.initDatatable();
                initOpenForm();
            },
            error: function (html) {
                modalContent.replaceAll('');
                modalContent.html(' <div class="modal-header"><strong>Došlo k neočekávané události.</strong><button type="button" class="close " data-dismiss="modal">×</button></div>');
            }
        });
    }
}

function initOpenForm() {
    $('.modal .vyrazene').click(function (event) {
        if ($(this).hasClass('btn-danger')) {
            $('.modal .d-none').removeClass('d-none');
            $(this).html("Skrýt");
            $(this).removeClass('btn-danger').addClass('btn-info');
        } else {
            $('.modal .bg-danger-lighter, .modal .bg-danger-light').addClass('d-none');
            $(this).html("Zobrazit");
            $(this).removeClass('btn-info').addClass('btn-danger');
        }
    });

    $('.open-form').click(function (event) {
        event.preventDefault();
        let a = $(this);
        let url = a.attr('href');
        if( a.data('url') !== undefined ){
            url = a.data('url');
        }
        openForm(url);
    })
}

$(document).ready(function (){
    initOpenForm();
});

$("#novyFormlModal").on('hidden.bs.modal', function () {
    window.location.reload();
});