// Import TinyMCE
import tinymce from 'tinymce/tinymce';

import 'tinymce-i18n/langs/cs.js';

// A theme is also required
import 'tinymce/themes/silver';

// Any plugins you want to use has to be imported
import 'tinymce/plugins/code';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';
import 'tinymce/plugins/print';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/directionality';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/visualchars';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/table';
import 'tinymce/plugins/hr';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/pagebreak';
import 'tinymce/plugins/toc';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/textcolor';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/contextmenu';
import 'tinymce/plugins/colorpicker';
import 'tinymce/plugins/textpattern';
import 'tinymce/plugins/help';

// Initialize the app
tinymce.init({
    selector: '.tinymce',
    language: 'cs',
    entity_encoding: 'raw',
    menubar: false,
    plugins: 'lists nonbreaking advlist code fullscreen',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat nonbreaking | fullscreen code',
    forced_root_block: 'div',
    relative_urls : false,
    remove_script_host : false,
});

tinymce.init({
    selector: '.tinymce-link',
    language: 'cs',
    menubar: false,
    height: 200,
    entity_encoding: 'raw',
    plugins: 'lists nonbreaking advlist code fullscreen link',
    toolbar: 'undo redo | bold italic underline | link unlink | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat nonbreaking | fullscreen code',
    forced_root_block: '',
    relative_urls : false,
    remove_script_host : false,
});

tinymce.init({
    selector: '.tinymce-aktuality',
    height: 500,
    language: 'cs',
    plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen link table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern help',
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    image_advtab: true,
    removed_menuitems: 'newdocument visualaid',
	relative_urls : false,
	remove_script_host : false,
});

tinymce.init({
    selector: '.tinymce-aktuality-kratke',
    height: 200,
    language: 'cs',
    plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen link table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern help',
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    image_advtab: true,
    removed_menuitems: 'newdocument visualaid',
	relative_urls : false,
	remove_script_host : false,
});
