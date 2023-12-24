"use strict";
var CustomerList = function () {
    var table, tableElement;

    var initDeleteRowEvent = () => {
        tableElement.querySelectorAll('[data-kt-customer-table-filter="delete_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                const seed_id = row.attributes['data-id'].value;
                const seedName = row.querySelectorAll("td")[1].innerText;
                Swal.fire({
                    text: "Bạn có chắc chắn muốn xoá " + seedName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đồng ý, xoá!",
                    cancelButtonText: "Không, huỷ",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/seed/delete',
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: seed_id
                            },
                            success: function (data) {
                                if (data.status === true) {
                                    Swal.fire({
                                        text: "Xoá thành công " + seedName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, đồng ý!",
                                        customClass: {confirmButton: "btn fw-bold btn-primary"}
                                    }).then((function () {
                                        table.row($(row)).remove().draw();
                                    }));
                                }
                            }
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: seedName + " chưa xoá được.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn fw-bold btn-primary"}
                        });
                    }
                }));
            }))
        }))
    };

    return {
        init: function () {
            tableElement = document.querySelector("#kt_customers_table");
            if (tableElement) {
                table = $(tableElement).DataTable({
                    info: false,
                    order: [],
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 3}]
                });

                table.on("draw", (function () {
                    initDeleteRowEvent();
                }));

                initDeleteRowEvent();

                document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function (event) {
                    table.search(event.target.value).draw();
                }));

                (()=>{
                    const businessFieldFilter = document.querySelector('[data-kt-ecommerce-order-filter="type"]');
                    $(businessFieldFilter).on("change", (event => {
                        let business_field_name = event.target.value;
                        if (business_field_name === "all") {
                            business_field_name = "";
                        }
                        table.column(2).search(business_field_name).draw();
                    }))
                })();
            }
        }
    }
}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.init();
}));
