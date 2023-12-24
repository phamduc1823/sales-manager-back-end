"use strict";

var CustomerModalHandler = function () {
    var submitButton, cancelButton, closeButton, formValidation, customerForm, customerModal,errorMessages;
    return {
        init: function () {
            customerModal = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer"));
            customerForm = document.querySelector("#kt_modal_add_customer_form");
            submitButton = customerForm.querySelector("#kt_modal_add_customer_submit");
            cancelButton = customerForm.querySelector("#kt_modal_add_customer_cancel");
            closeButton = customerForm.querySelector("#kt_modal_add_customer_close");
            formValidation = FormValidation.formValidation(customerForm, {
                fields: {
                    business_field_id: { validators: { notEmpty: { message: "Ngành hàng không được để trống" } } },
                    seed_id: { validators: { notEmpty: { message: "Loại dữ liệu mẫu không được để trống" } } },
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
                    let business_field_seeds_id = customerForm.querySelector('[name="id"]')?customerForm.querySelector('[name="id"]').value:"";
                    if ("Valid" == modalForm) {
                        (submitButton.setAttribute("data-kt-indicator", "on"), submitButton.disabled = !0,
                            $.ajax({
                                url: business_field_seeds_id!=""?'/admin/data-seed/update':'/admin/data-seed/store',
                                method: business_field_seeds_id!=""?'PUT':'POST',
                                data: {
                                    _token: customerForm.querySelector('[name="_token"]').value,
                                    business_field_id: customerForm.querySelector('[name="business_field_id"]').value,
                                    seed_id: customerForm.querySelector('[name="seed_id"]').value,
                                    id: business_field_seeds_id!=""?business_field_seeds_id:null
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
