"use strict";

var CustomerModalHandler = function () {
    var submitButton, cancelButton, closeButton, formValidation, customerForm, customerModal, detailModal,
        closeButtonDetail, detailForm, cancelButtonDetail, errorMessages;
    return {
        init: function () {
            customerModal = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer"));
            customerForm = document.querySelector("#kt_modal_add_customer_form");
            submitButton = customerForm.querySelector("#kt_modal_add_customer_submit");
            cancelButton = customerForm.querySelector("#kt_modal_add_customer_cancel");
            closeButton = customerForm.querySelector("#kt_modal_add_customer_close");
            formValidation = FormValidation.formValidation(customerForm, {
                fields: {
                    tenant: {validators: {notEmpty: {message: "Tên cửa hàng không được để trống"}}},
                    pricing: {validators: {notEmpty: {message: "Gói không được để trống"}}},
                    type: {validators: {notEmpty: {message: "Loại yêu cầu không được để trống"}}},
                    name: {validators: {notEmpty: {message: "Tên khách hàng không được để trống"}}},
                    tel: {
                        validators: {
                            notEmpty: {message: "Số điện thoại liên hệ không được để trống"},
                            phone: {country: "VN", message: "Số điện thoại không hợp lệ"}
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });

            submitButton.addEventListener("click", function (event) {
                event.preventDefault();
                formValidation && formValidation.validate().then(function (modalForm) {
                    console.log("validated!");
                    if ("Valid" == modalForm) {
                        (submitButton.setAttribute("data-kt-indicator", "on"), submitButton.disabled = !0,
                            $.ajax({
                                url: '/admin/order/store',
                                method: 'POST',
                                data: {
                                    _token: customerForm.querySelector('[name="_token"]').value,
                                    tenant_id: customerForm.querySelector('[name="tenant"]').value,
                                    pricing_id: customerForm.querySelector('[name="pricing"]').value,
                                    type: customerForm.querySelector('[name="type"]').value,
                                    name: customerForm.querySelector('[name="name"]').value,
                                    tel: customerForm.querySelector('[name="tel"]').value,
                                    note: customerForm.querySelector('[name="note"]').value,
                                },
                                success: function (data) {
                                    submitButton.removeAttribute("data-kt-indicator"), submitButton.disabled = !1,
                                        Swal.fire({
                                            text: data.payload,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        }).then((function (modalForm) {
                                            modalForm.isConfirmed && customerModal.hide()
                                            window.location.reload()
                                        }))
                                }, error: function (data) {
                                    submitButton.removeAttribute("data-kt-indicator"), submitButton.disabled = !1,
                                     errorMessages = data.responseJSON.meta.errors;
                                    $.each(errorMessages, function(key, value) {
                                        errorMessages="Lỗi : " + value.join(", ");
                                    });
                                    Swal.fire({
                                        text: errorMessages,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, đồng ý!",
                                        customClass: {confirmButton: "btn btn-primary"}
                                    })
                                }
                            }))
                    } else {
                        Swal.fire({
                            text: "Xin lỗi, đã có lỗi xảy ra xin vui lòng thử lại.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }
                });
            });

            cancelButton.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation(customerForm, customerModal);
            });

            closeButton.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation(customerForm, customerModal);
            });
            //show detail
            detailModal = new bootstrap.Modal(document.querySelector("#kt_modal_show_detail"));
            detailForm = document.querySelector("#kt_modal_show_detail_form");
            closeButtonDetail = detailForm.querySelector("#kt_modal_show_detail_close");
            cancelButtonDetail = detailForm.querySelector("#kt_modal_show_detail_cancel");
            closeButtonDetail.addEventListener("click", function (event) {
                event.preventDefault();
                detailModal.hide();
            })
            cancelButtonDetail.addEventListener("click", function (event) {
                event.preventDefault();
                detailModal.hide();
            })
            //add note
            let addNoteModal = new bootstrap.Modal(document.querySelector("#kt_modal_add_note"));
            let addNoteForm = document.querySelector("#kt_modal_add_note_form");
            let addNoteButtonSubmit = addNoteForm.querySelector("#kt_modal_add_note_submit");
            let addNoteButtonCancel = addNoteForm.querySelector("#kt_modal_add_note_cancel");
            let addNoteButtonClose = addNoteForm.querySelector("#kt_modal_add_note_close");
            let addNoteFormValidation = FormValidation.formValidation(addNoteForm, {
                fields: {
                    note: {validators: {notEmpty: {message: "Nội dung không được để trống"}}},
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });
            addNoteButtonSubmit.addEventListener("click", function (event) {
                event.preventDefault();
                addNoteFormValidation && addNoteFormValidation.validate().then(function (modalForm) {
                    console.log("validated!");
                    if ("Valid" == modalForm) {
                        (addNoteButtonSubmit.setAttribute("data-kt-indicator", "on"), addNoteButtonSubmit.disabled = !0,
                            $.ajax({
                                url: '/admin/order/create-note',
                                method: 'POST',
                                data: {
                                    _token: addNoteForm.querySelector('[name="_token"]').value,
                                    subscription_order_id: addNoteForm.querySelector('[name="subscription_order_id"]').value,
                                    note: addNoteForm.querySelector('[name="note"]').value,
                                },
                                success: function (data) {
                                    addNoteButtonSubmit.removeAttribute("data-kt-indicator"), addNoteButtonSubmit.disabled = !1,
                                        Swal.fire({
                                            text: data.payload,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        }).then((function (modalForm) {
                                            modalForm.isConfirmed && addNoteModal.hide()
                                            window.location.reload()
                                        }))
                                }, error: function (data) {
                                    addNoteButtonSubmit.removeAttribute("data-kt-indicator"), addNoteButtonSubmit.disabled = !1,
                                        Swal.fire({
                                            text: data.responseJSON.meta.errors.name,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        })
                                }
                            }))
                    } else {
                        Swal.fire({
                            text: "Xin lỗi, đã có lỗi xảy ra xin vui lòng thử lại.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }
                });
            })
            addNoteButtonCancel.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation(addNoteForm, addNoteModal);
            })
            addNoteButtonClose.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation(addNoteForm, addNoteModal);
            })


            function showCancelConfirmation(element, modal) {
                Swal.fire({
                    text: "Bạn chắc chắn muốn huỷ ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đúng, huỷ nó!",
                    cancelButtonText: "Không, trở lại",
                    customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                }).then(function (result) {
                    if (result.value) {
                        element.reset();
                        modal.hide();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            text: "Biểu mẫu của bạn đã được huỷ!",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        });
                    }
                });
            }
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    CustomerModalHandler.init();
});
