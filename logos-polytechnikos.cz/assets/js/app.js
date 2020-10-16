/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// GLOBALNI STYLY - pro mozne soubory k aktivaci viz obsah souboru nize
import '../css/app.scss';
// GLOBALNI STYLY

// GLOBALNI JS - pro mozne soubory k aktivaci viz obsah souboru nize
import '@fortawesome/fontawesome-free';
import 'bootstrap';
import 'bootstrap4-toggle'
import './collection-init';
import './datatable';
import './select2';
import './tinymce';
import './modal-ajax';

// GLOBALNI JS


import 'promise-polyfill';
import 'whatwg-fetch';
import 'classlist-polyfill';
import elementDatasetPolyfill from "element-dataset";
elementDatasetPolyfill();

if (module.hot) {
    module.hot.accept();
}
