import './select2.js'

import '../css/datatable.scss';

import 'datatables.net-dt';
import 'datatables.net-bs4';
import 'datatables.net-plugins/sorting/intl.js';
$( document ).ready(function() {
    var dataTableCZ = {
        "decimal": ",",
        "thousands": " ",
        "sEmptyTable":     "Tabulka neobsahuje žádná data",
        "sInfo":           "Zobrazuji _START_ až _END_ z celkem _TOTAL_ záznamů",
        "sInfoEmpty":      "Zobrazuji 0 až 0 z 0 záznamů",
        "sInfoFiltered":   "(filtrováno z celkem _MAX_ záznamů)",
        "sInfoPostFix":    "",
        "sInfoThousands":  " ",
        "sLengthMenu":     "_MENU_",
        "sLoadingRecords": "Načítám...",
        "sProcessing":     "Provádím...",
        "sSearch":         "",
        "searchPlaceholder":         "Hledat",
        "sZeroRecords":    "Žádné záznamy nebyly nalezeny",
        "oPaginate": {
            "sFirst":    "První",
            "sLast":     "Poslední",
            "sNext":     "Další",
            "sPrevious": "Předchozí"
        },
        "oAria": {
            "sSortAscending":  ": aktivujte pro řazení sloupce vzestupně",
            "sSortDescending": ": aktivujte pro řazení sloupce sestupně"
        }
    };

        $('.datatable_default').DataTable(
            {
                "language": dataTableCZ,
                "pageLength": 50
            }
        );

        $('.datatable').DataTable(
            {
                "paging": false,
                "language": dataTableCZ
            }
        );

    $('.datatable_nosort').DataTable(
        {
            "order": [],
            "paging": false,
            "language": dataTableCZ
        }
    );

        $('.datatable_nosearch').DataTable(
            {
                "paging": false,
                "searching": false,
                "language": dataTableCZ
            }
        );
    $.fn.dataTable.ext.order.intl('cs');

    function cbDropdown(column) {
        return $('<ul>', {
            'class': 'cb-dropdown'
        }).appendTo($('<div>', {
            'class': 'cb-dropdown-wrap'
        }).appendTo(column));
    }

    $('.datatable_filter:not(.DataTable)').DataTable( {
        paging: true,
        lengthMenu: [[100, 200, 300, -1], [100, 200, 300, 'Zobrazit vše']],
        pageLength: -1,
        language: dataTableCZ,
        columnDefs: {targets: 'datatable-ignore', orderable: false},
        initComplete: function () {
            var filterTr = $('<tr class="filter-row">').appendTo($(this).find('thead'));
            this.api().columns().every( function () { //stejné v datatable_filter_multiple, ale liší se v on('change', ...) a inicializaci select2
                var column = this;
                var filterTh = $('<th>').appendTo(filterTr);
                if(!$(column.header()).hasClass('datatable-ignore')){
                    var select = $('<select class="datatable-filter"><option value="">Vše</option></select>')
                        .appendTo(filterTh)
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    var values = [];
                    var v;
                    column.table().data().each(function (d, j) {
                        var dc = d[column.index()];
                        if(typeof dc === 'object'){
                            if(dc['@data-filter'] !== undefined){
                                v = dc['@data-filter'];
                            } else {
                                v = dc['display'];
                            }
                        } else {
                            v = dc;
                        }

                        values.sort();
                        if($.inArray(v, values) === -1) {
                            values.push(v);
                        }
                    });
                    $.each(values, function(key, value){
                        select.append('<option value="' + value + '">' + value + '</option>')
                    });
                }
            } );
            // inicializuj vsechny html "selecty" s tridou "select2", ktere nemaji tridu "select2-hidden-accessible"
            // (tzn. ktere jeste nejsou inicializovany, pozn.: v datatable uz mohou byt inicializovany)
            $('select.datatable-filter:not(.select2-hidden-accessible)').select2({
                //theme: "bootstrap4",
                language: "cs"
            });
        }
    } );

    $('.datatable_filter_multiple:not(.DataTable)').DataTable( {
		dom: '<"top"<"sirka-2"f><"sirka-1"i>l<"clear">>rt<"bottom"<"sirka-1"i>lp<"clear">>',
        paging: true,
        lengthMenu: [[100, 200, 300, -1], [100, 200, 300, 'Zobrazit vše']],
        pageLength: -1,
        language: dataTableCZ,
        columnDefs: {targets: 'datatable-ignore', orderable: false},
        initComplete: function () {
            var filterTr = $('<tr>').appendTo($(this).find('thead'));
            this.api().columns().every( function () { //stejné v datatable_filter, ale liší se v on('change', ...)  a inicializaci select2
                var column = this;
                var filterTh = $('<th>').appendTo(filterTr);
                var zmenitOrder = $(column.header()).hasClass('datatable-order-desc');
                if(!$(column.header()).hasClass('datatable-ignore')){
                    var select = $('<select class="datatable-filter"></select>')
                        .appendTo(filterTh)
                        .on( 'change', function () {
                            //Get the "text" property from each selected data
                            //regex escape the value and store in array
                            var data = $.map( $(this).select2('data'), function( value, key ) {
                                return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                            });

                            //if no data selected use ""
                            if (data.length === 0) {
                                data = [""];
                            }

                            //join array into string with regex or (|)
                            var val = data.join('|');

                            //search for the option(s) selected
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );

                    var values = [];
                    var v;
                    column.table().data().each(function (d, j) {
                        var dc = d[column.index()];
                        if(typeof dc === 'object'){
                            if(dc['@data-filter'] !== undefined){
                                v = dc['@data-filter'];
                            } else {
                                v = dc['display'];
                            }
                        } else {
                            v = dc;
                        }

                        values.sort();
                        if($.inArray(v, values) === -1) {
							values.push(v);
						}
                        if (zmenitOrder){
                        	values.reverse();
						}
					});
                    $.each(values, function(key, value){
                        select.append('<option value="' + value + '">' + value.slice(value.indexOf('~', 0) > 0 ? value.indexOf('~', 0) + 1 : 0, value.length) + '</option>')
                    });
                }
            } );
            // inicializuj vsechny html "selecty" s tridou "select2", ktere nemaji tridu "select2-hidden-accessible"
            // (tzn. ktere jeste nejsou inicializovany, pozn.: v datatable uz mohou byt inicializovany)
            $('select.datatable-filter:not(.select2-hidden-accessible)').select2({
                multiple: true,
                language: "cs",
                placeholder: "Vyberte"
            }).val(null).trigger('change'); //musím setnout na null, jinak se vybere první řádek
        }
    } );

    $.each(['.datatable_default', '.datatable', '.datatable_nosort', '.datatable_nosearch', '.datatable_filter', '.datatable_filter_multiple'], function(index, value){
        var t = $(value).DataTable();
        t.on( 'order.dt search.dt', function () {
            var index = null;
            $(this).find('thead th').each(function(i){
                if($(this).hasClass('datatable-position')){
                    index = i;
                }
            });
            if(index !== null) {
                t.column(index, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + '. ';
                });
            }
        } ).draw();
    });

	$.each(['.datatable_default', '.datatable', '.datatable_nosort', '.datatable_nosearch', '.datatable_filter', '.datatable_filter_multiple'], function(index, value){
		var t = $(value).DataTable();
		t.on( 'order.dt', function () {
			var index = null;
			$(this).find('thead th').each(function(i){
				if($(this).hasClass('datatable-position-only-order')){
					index = i;
				}
			});
			if(index !== null) {
				t.column(index, {order: 'applied'}).nodes().each(function (cell, i) {
					cell.innerHTML = i + 1 + '. ';
				});
			}
		}).draw();
	});

});
