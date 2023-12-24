"use strict";
var CustomerList = function () {
    var table, tableElement;

    var initDeleteRowEvent = () => {
        tableElement.querySelectorAll('[data-kt-customer-table-filter="delete_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                const subscriptionOrderId = row.attributes['data-id'].value;
                const customerName = row.querySelectorAll("td")[1].innerText;
                Swal.fire({
                    text: "Bạn có chắc chắn muốn xoá " + customerName + "?",
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
                            url: '/admin/order/delete',
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: subscriptionOrderId
                            },
                            success: function (data) {
                                if (data.status === 200) {
                                    Swal.fire({
                                        text: "Xoá thành công " + customerName + "!.",
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
                            text: customerName + " chưa xoá được.",
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
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 8}]
                });

                table.on("draw", (function () {
                    initDeleteRowEvent();
                }));

                initDeleteRowEvent();

                document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function (event) {
                    table.search(event.target.value).draw();
                }));

                (() => {
                    const typeSeedFilter = document.querySelector('[data-kt-ecommerce-order-filter="type"]');
                    $(typeSeedFilter).on("change", (event => {
                        let type = event.target.value;
                        if (type === "all") {
                            type = "";
                        }
                        table.column(4).search(type).draw();
                    }))
                })();
                (()=>{
                    const businessFieldFilter = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
                    $(businessFieldFilter).on("change", (event => {
                        let status = event.target.value;
                        if (status === "all") {
                            status = "";
                        }
                        table.column(7).search(status).draw();
                    }))
                })();
            }
        }
    }
}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.init();
}));
