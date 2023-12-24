"use strict";
var PricingList = function () {
    var dataTable, deleteRow;
    const tableElement = document.getElementById('kt_table_pricing');
    deleteRow = () => {
        tableElement.querySelectorAll('[data-kt-pricing-table-filter="delete_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                let pricing_id = row.attributes['data-id'].value;
                let pricing_name = row.querySelectorAll('td')[0].innerText;

                Swal.fire({
                    text: "Bạn có chắc muốn xoá " + pricing_name + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đồng ý, xoá!",
                    cancelButtonText: "Không, huỷ bỏ!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/pricing/delete',
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: pricing_id
                            },
                            success: function (data) {
                                Swal.fire({
                                    text: "Xoá thành công " + pricing_name + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, đồng ý!",
                                    customClass: {confirmButton: "btn fw-bold btn-primary"}
                                }).then((function () {
                                    dataTable.row($(row)).remove().draw();
                                }));
                            }
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: pricing_name + " chưa được xoá.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn fw-bold btn-primary"}
                        });
                    }
                }));
            }));
        }));
    };

    return {
        init: function () {
            dataTable = document.getElementById("kt_table_pricing");

            if (dataTable) {
                dataTable = $(dataTable).DataTable({
                    info: false,
                    order: [],
                    pageLength: 10,
                    lengthChange: false,
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 5}]
                });

                dataTable.on("draw", (function () {
                    deleteRow();
                }));

                deleteRow();

                document.querySelector('[data-kt-pricing-table-filter="search"]').addEventListener("keyup", (function (event) {
                    dataTable.search(event.target.value).draw();
                }));
            }
        }
    };
}();

KTUtil.onDOMContentLoaded((function () {
    PricingList.init();
}));
