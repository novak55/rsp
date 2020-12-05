import '../css/app.scss';
import '@fortawesome/fontawesome-free';
import 'bootstrap';
import 'bootstrap4-toggle'
import './collection-init';
import './select2';
import './tinymce';
import './modal-ajax';
import 'promise-polyfill';
import 'whatwg-fetch';
import 'classlist-polyfill';
import elementDatasetPolyfill from "element-dataset";
elementDatasetPolyfill();

if (module.hot) {
    module.hot.accept();
}

$('[data-toggle=tooltip]').tooltip();