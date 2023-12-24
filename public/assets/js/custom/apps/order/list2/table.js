"use strict";
var CustomerList = function () {
    var table, tableElement;
    return {
        init: function () {
            tableElement = document.querySelector("#kt_customers_table");
            if (tableElement) {
                table = $(tableElement).DataTable({
                    info: false,
                    order: [],
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 8}]
                });

                document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function (event) {
                    table.search(event.target.value).draw();
                }));
                (()=>{
                    const typeFieldFilter = document.querySelector('[data-kt-ecommerce-order-filter="type"]');
                    $(typeFieldFilter).on("change", (event => {
                        let typeName = event.target.value;
                        if (typeName === "all") {
                            typeName = "";
                        }
                        table.column(2).search(typeName).draw();
                    }))
                })();
            }
        }
    }
}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.init();
}));
