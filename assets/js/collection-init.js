if (module.hot) {
    module.hot.accept();
}

import 'symfony-collection/jquery.collection.js';

$(function () {
    $('.collection').collection({
        add: '',
        add_at_the_end: true,
        allow_down: false,
        allow_up: false,
        allow_remove: false,
        allow_duplicate: false,
        remove: '',
        after_add: function(collection, element) {
            $('.collection').collection({
                add: '',
                remove: '',
                prefix: 'collection',
                add_at_the_end: true,
                allow_down: false,
                allow_up: false,
                allow_remove: false,
                allow_duplicate: false
            });
        }
    });
});