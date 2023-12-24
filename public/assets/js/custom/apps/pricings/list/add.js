"use strict";
const KTPricingAddPricing = function () {
    const modalForm = document.getElementById("kt_modal_add_pricing"),
        form = modalForm.querySelector("#kt_modal_add_pricing_form"),
        modal = new bootstrap.Modal(modalForm);
    const isNaturalNumber = (value) => {
        const number = Number(value);
        return Number.isInteger(number) && number >= 0;
    };
    var errorMessages ;
    return {
        init: function () {
            (() => {
                var checkForm = FormValidation.formValidation(form, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {message: "Tên không được để trống"},
                                stringLength: {min: 3, message: "Tên phải có ít nhất 3 ký tự"},
                            }
                        },
                        max_locations: {
                            validators: {
                                notEmpty: {message: "Số chi nhánh tối đa không được để trống"},
                                integer: {message: "Số chi nhánh tối đa phải là một số nguyên"},
                                greaterThan: {
                                    value: 0,
                                        inclusive: false,
                                        message: "Số chi nhánh tối đa phải lớn hơn 0",
                                        min: 0
                                }
                            }
                        },
                        max_users: {validators: {notEmpty: {message: "Số người dùng tối đa không được để trống"},
                                integer: {message: "Số người dùng tối đa phải là một số nguyên"},
                                greaterThan: {
                                    value: 0,
                                    inclusive: false,
                                    message: "Số người dùng tối đa phải lớn hơn 0",
                                    min: 0
                                }
                            }},
                        price_per_month: {validators: {notEmpty: {message: "Giá không được để trống"},
                                integer: {message: "Giá phải là một số nguyên"},
                                greaterThan: {
                                    value: -1,
                                    inclusive: false,
                                    message: "Giá phải lớn hơn 0",
                                    min: -1
                                }
                            }},
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                });
                const btn_submit = modalForm.querySelector('[data-kt-pricing-modal-action="submit"]');
                btn_submit.addEventListener("click", (modalForm => {
                    let pricing_id = form.querySelector('[name="id"]') ? form.querySelector('[name="id"]').value : "";
                    modalForm.preventDefault(), checkForm && checkForm.validate().then((function (modalForm) {
                        if ("Valid" == modalForm) {
                            (btn_submit.setAttribute("data-kt-indicator", "on"), btn_submit.disabled = !0,
                                $.ajax({
                                    url: pricing_id != "" ? '/admin/pricing/update' : '/admin/pricing/store',
                                    method: pricing_id != "" ? 'PUT' : 'POST',
                                    data: {
                                        _token: form.querySelector('[name="_token"]').value,
                                        name: form.querySelector('[name="name"]').value,
                                        max_locations: form.querySelector('[name="max_locations"]').value,
                                        max_users: form.querySelector('[name="max_users"]').value,
                                        price: form.querySelector('[name="price"]').value,
                                        expiry_day: form.querySelector('[name="expiry_day"]').value,
                                        id: pricing_id != "" ? pricing_id : null
                                    },
                                    success: function (data) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1,
                                            Swal.fire({
                                                text: data.payload,
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, đồng ý!",
                                                customClass: {confirmButton: "btn btn-primary"}
                                            }).then((function (modalForm) {
                                                modalForm.isConfirmed && modal.hide()
                                                window.location.reload()
                                            }))
                                    },      error: function (data) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1,
                                            errorMessages = data.responseJSON.meta.errors;
                                        $.each(errorMessages, function (key, value) {
                                            errorMessages = "Lỗi : " + value.join(", ");
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
                    }))
                }))
                modalForm.querySelector('[data-kt-pricing-modal-action="cancel"]').addEventListener("click", (modalForm => {
                    modalForm.preventDefault(), Swal.fire({
                        text: "Bạn có chắc chắn muốn huỷ?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Đúng, huỷ nó!",
                        cancelButtonText: "Không, trở lại",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (modalForm) {
                        modalForm.value ? (form.reset(), modal.hide()) : "cancel" === modalForm.dismiss && Swal.fire({
                            text: "Biểu mẫu của bạn chưa bị hủy!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                })), modalForm.querySelector('[data-kt-pricing-modal-action="close"]').addEventListener("click", (modalForm => {
                    modalForm.preventDefault(), Swal.fire({
                        text: "Bạn có chắc chắn muốn huỷ?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Đúng, huỷ nó!",
                        cancelButtonText: "Không, trở lại",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (modalForm) {
                        modalForm.value ? (form.reset(), modal.hide()) : "cancel" === modalForm.dismiss && Swal.fire({
                            text: "Biểu mẫu của bạn chưa bị hủy!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Đúng, huỷ nó!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }))
            })()
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTPricingAddPricing.init()
}));
