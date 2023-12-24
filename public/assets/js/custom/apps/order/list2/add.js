"use strict";

var CustomerModalHandler = function () {
    var submitButton, cancelButton, closeButton, formValidation, customerForm, customerModal;
    return {
        init: function () {
            customerModal = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer"));
            customerForm = document.querySelector("#kt_modal_add_customer_form");
            submitButton = customerForm.querySelector("#kt_modal_add_customer_submit");
            cancelButton = customerForm.querySelector("#kt_modal_add_customer_cancel");
            closeButton = customerForm.querySelector("#kt_modal_add_customer_close");
            formValidation = FormValidation.formValidation(customerForm, {
                fields: {
                    payment_method: { validators: { notEmpty: { message: "Ngành hàng không được để trống" } } },
                    id: { validators: { notEmpty: { message: "ID không được để trống" } } },
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
                    let order_id = customerForm.querySelector('[name="id"]')?customerForm.querySelector('[name="id"]').value:"";
                    if ("Valid" == modalForm) {
                        (submitButton.setAttribute("data-kt-indicator", "on"), submitButton.disabled = !0,
                            $.ajax({
                                url:'/admin/order/payment',
                                method: 'POST',
                                data: {
                                    _token: customerForm.querySelector('[name="_token"]').value,
                                    payment_method: customerForm.querySelector('[name="payment_method"]').value,
                                    reference_code: customerForm.querySelector('[name="reference_code"]').value,
                                    id: order_id
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
            });

            cancelButton.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation();
            });

            closeButton.addEventListener("click", function (event) {
                event.preventDefault();
                showCancelConfirmation();
            });

            function showCancelConfirmation() {
                Swal.fire({
                    text: "Bạn chắc chắn muốn huỷ ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đúng, huỷ nó!",
                    cancelButtonText: "Không, trở lại",
                    customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light" }
                }).then(function (result) {
                    if (result.value) {
                        customerForm.reset();
                        customerModal.hide();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            text: "Biểu mẫu của bạn đã được huỷ!",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: { confirmButton: "btn btn-primary" }
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
