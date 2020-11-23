import '../../css/article/add_article.scss';

import '../custom-file-input';
import '../modal-confirm'

if (module.hot) {
    module.hot.accept();
}

$(function () {
    $('#article_type_article_addCollaborator').on('click', function (e) {
        let collaborator = $('#article_type_article_nameCollaborator');
        let email = $('#article_type_article_email');
        if($.trim(collaborator.val()) !== '' && $.trim(email.val()) !== ''){
            return true;
        }
        e.preventDefault();
        if($.trim(collaborator.val()) === '') {
            collaborator.addClass('bg-danger');
            collaborator.attr("placeholder", 'Jména spoluautora je nutné vyplnit');
        }
        if($.trim(email.val()) === '') {
            email.addClass('bg-danger');
            email.attr("placeholder", 'E-mail spoluatora je nutné vyplnit');
        }
        return false;
    });

});