"use strict";
var BusinessFieldList = function () {
    var dataTable, deleteRow, restoreRow;
    const tableElement = document.getElementById('kt_table_business_field');
    deleteRow = () => {
        tableElement.querySelectorAll('[data-kt-business_field-table-filter="delete_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                let business_field_id = row.attributes['data-id'].value;
                let business_field_name = row.querySelectorAll('td')[0].innerText;
                Swal.fire({
                    text: "Bạn có chắc muốn xoá " + business_field_name + "?",
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
                            url: (window.location.href + '/delete').replace('#', ''),
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: business_field_id
                            },
                            success: function (data) {
                                Swal.fire({
                                    text: "Xoá thành công " + business_field_name + "!.",
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
                            text: business_field_name + " chưa được xoá.",
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

    restoreRow = () => {
        tableElement.querySelectorAll('[data-kt-business_field-table-filter="restore_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                let business_field_id = row.attributes['data-id'].value;
                let business_field_name = row.querySelectorAll('td')[0].innerText;
                Swal.fire({
                    text: "Bạn có chắc muốn khôi phục " + business_field_name + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đồng ý, khôi phục!",
                    cancelButtonText: "Không, huỷ bỏ!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (result) {
                    if (result.value) {
                        $.ajax({
                            url: window.location.href.replace('trash', 'restore'),
                            type: 'get',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: business_field_id
                            },
                            success: function (data) {
                                console.log(data)
                                Swal.fire({
                                    text: "Khôi phục thành công " + business_field_name + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, đồng ý!",
                                    customClass: {confirmButton: "btn fw-bold btn-primary"}
                                }).then((function () {
                                    window.location.replace(window.location.href.replace('/trash', ''));
                                }));
                            }
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: business_field_name + " chưa được khôi phục.",
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
            dataTable = document.getElementById("kt_table_business_field");

            if (dataTable) {
                dataTable = $(dataTable).DataTable({
                    info: false,
                    order: [],
                    pageLength: 10,
                    lengthChange: false,
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 3}]
                });

                dataTable.on("draw", (function () {
                    deleteRow();
                }));

                deleteRow();

                document.querySelector('[data-kt-business_field-table-filter="search"]').addEventListener("keyup", (function (event) {
                    dataTable.search(event.target.value).draw();
                }));

                restoreRow();
            }
        }
    };
}();

KTUtil.onDOMContentLoaded((function () {
    BusinessFieldList.init();
}));
