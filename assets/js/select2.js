import '../css/select2.scss';

import 'select2';
import 'select2/dist/js/i18n/cs.js'

$(document).ready(function() {
    // inicializuj vsechny html "selecty" s tridou "select2", ktere nemaji tridu "select2-hidden-accessible"
    // (tzn. ktere jeste nejsou inicializovany, pozn.: v datatable uz mohou byt inicializovany)
    $('select.select2:not(.select2-hidden-accessible)').select2({
        language: "cs"
    });
});
