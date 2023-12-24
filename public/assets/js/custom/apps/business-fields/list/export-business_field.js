"use strict";

// Class definition
var KTExportPricing = function () {
    // Shared variables
    let table;
    // Hook export buttons

    const exportButtons = () => {
        const documentTitle = 'Pricings Report';
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: documentTitle
                },
                {
                    extend: 'excelHtml5',
                    title: documentTitle
                },
                {
                    extend: 'csvHtml5',
                    title: documentTitle
                },
                {
                    extend: 'pdfHtml5',
                    title: documentTitle
                }
            ]
        }).container().appendTo($('#kt_datatable_pricing_buttons'));

        // Hook dropdown menu click event to datatable export buttons
        const exportButtons = document.querySelectorAll('#kt_datatable_pricing_export_menu [data-kt-export]');
        exportButtons.forEach(exportButton => {
            exportButton.addEventListener('click', e => {
                e.preventDefault();

                // Get clicked export value
                const exportValue = e.target.getAttribute('data-kt-export');
                const target = document.querySelector('.dt-buttons .buttons-' + exportValue);
                // Trigger click event on hidden datatable export buttons
                target.click();
            });
        });
    }


    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_table_pricing');

            if (!table) {
                return;
            }
            exportButtons();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTExportPricing.init();
});
