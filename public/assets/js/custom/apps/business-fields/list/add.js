"use strict";
const KTbusiness_fieldAddbusiness_field = function () {
    const modalForm = document.getElementById("kt_modal_add_business_field"),
        form = modalForm.querySelector("#kt_modal_add_business_field_form"), modal = new bootstrap.Modal(modalForm);
    var checkForm, errorMessages;
    const initFormValidation = (fields) => {
        checkForm = FormValidation.formValidation(form, {
            fields: fields, plugins: {
                trigger: new FormValidation.plugins.Trigger, bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: ""
                })
            }
        });
    }
    const removeAccent = (str) => {
        return str.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d").replace(/Đ/g, "D");
    }
    return {
        init: function () {
            (() => {
                initFormValidation({
                    email_user: form.querySelector('[name="email_user"]') ? {
                        validators: {
                            notEmpty: {message: "Email không được để trống"}, regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "Email không hợp lệ"
                            }
                        }
                    } : null,
                    ten_user: form.querySelector('[name="ten_user"]') ? {validators: {notEmpty: {message: "Tên người dùng không được để trống"}}} : null,
                    name: form.querySelector('[name="name"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                    code: form.querySelector('[name="code"]') ? {validators: {notEmpty: {message: "Mã ngành hàng không được để trống"},}} : null,
                    detail: form.querySelector('[name="detail"]') ? {validators: {notEmpty: {message: "Mô tả không được để trống"}}} : null,
                    name_tenant: form.querySelector('[name="name_tenant"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                    user_id: form.querySelector('[name="user_id"]') ? {validators: {notEmpty: {message: "Chọn user"}}} : null,
                    business_field: form.querySelector('[name="business_field"]') ? {validators: {notEmpty: {message: "Chọn lĩnh vực kinh doanh"}}} : null,
                    due_at: form.querySelector('[name="due_at"]') ? {validators: {notEmpty: {message: "Chọn ngày hết hạn"}}} : null,
                    pricing_id: form.querySelector('[name="pricing_id"]') ? {validators: {notEmpty: {message: "Chọn gói dịch vụ"}}} : null,
                    business_name: form.querySelector('[name="business_name"]') ? {validators: {notEmpty: {message: "Tên cửa hàng phải được nhập"}}} : null,
                    address: form.querySelector('[name="address"]') ? {validators: {notEmpty: {message: "Địa chỉ phải được nhập"}}} : null,
                    password: (form.querySelector('[name="password"]') && form.querySelector('[name="id"]') === null) ? {validators: {notEmpty: {message: "Mật khẩu không được để trống"}}} : null,
                    role: form.querySelector('[name="role"]') ? {validators: {notEmpty: {message: "Chọn vai trò"}}} : null,
                });
                const btn_submit = modalForm.querySelector('[data-kt-business_field-modal-action="submit"]');
                btn_submit.addEventListener("click", (modalForm => {
                    let business_field_id = form.querySelector('[name="id"]') ? form.querySelector('[name="id"]').value : null;
                    if (business_field_id) {
                        initFormValidation({
                            email_user: form.querySelector('[name="email_user"]') ? {
                                validators: {
                                    notEmpty: {message: "Email không được để trống"}, regexp: {
                                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: "Email không hợp lệ"
                                    }
                                }
                            } : null,
                            ten_user: form.querySelector('[name="ten_user"]') ? {validators: {notEmpty: {message: "Tên người dùng không được để trống"}}} : null,
                            name: form.querySelector('[name="name"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                            code: form.querySelector('[name="code"]') ? {validators: {notEmpty: {message: "Mã ngành hàng không được để trống"},}} : null,
                            detail: form.querySelector('[name="detail"]') ? {validators: {notEmpty: {message: "Mô tả không được để trống"}}} : null,
                            name_tenant: form.querySelector('[name="name_tenant"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                            user_id: form.querySelector('[name="user_id"]') ? {validators: {notEmpty: {message: "Chọn user"}}} : null,
                            business_field: form.querySelector('[name="business_field"]') ? {validators: {notEmpty: {message: "Chọn lĩnh vực kinh doanh"}}} : null,
                            due_at: form.querySelector('[name="due_at"]') ? {validators: {notEmpty: {message: "Chọn ngày hết hạn"}}} : null,
                            pricing_id: form.querySelector('[name="pricing_id"]') ? {validators: {notEmpty: {message: "Chọn gói dịch vụ"}}} : null,
                            business_name: form.querySelector('[name="business_name"]') ? {validators: {notEmpty: {message: "Tên cửa hàng phải được nhập"}}} : null,
                            address: form.querySelector('[name="address"]') ? {validators: {notEmpty: {message: "Địa chỉ phải được nhập"}}} : null,
                            role: form.querySelector('[name="role"]') ? {validators: {notEmpty: {message: "Chọn vai trò"}}} : null,
                        });
                    }
                    modalForm.preventDefault(), checkForm && checkForm.validate().then((function (modalForm) {
                        if ("Valid" === modalForm) {
                            (btn_submit.setAttribute("data-kt-indicator", "on"), btn_submit.disabled = !0, $.ajax({
                                url: business_field_id? (window.location.href + '/update').replace('#', '') : (window.location.href + '/store').replace('#', ''),
                                method: business_field_id ? 'PUT' : 'POST',
                                data: {
                                    _token: form.querySelector('[name="_token"]').value,
                                    business_name: form.querySelector('[name="business_name"]') ? form.querySelector('[name="business_name"]').value : null,
                                    address: form.querySelector('[name="address"]') ? form.querySelector('[name="address"]').value : null,
                                    name: form.querySelector('[name="name"]') ? form.querySelector('[name="name"]').value : null,
                                    code: form.querySelector('[name="code"]') ? form.querySelector('[name="code"]').value.toUpperCase() : null,
                                    detail: form.querySelector('[name="detail"]') ? form.querySelector('[name="detail"]').value : null,
                                    name_tenant: form.querySelector('[name="name_tenant"]') ? removeAccent(form.querySelector('[name="name_tenant"]').value.replace(/\s/g, "")) : null,
                                    business_field: form.querySelector('[name="business_field"]') ? form.querySelector('[name="business_field"]').value : null,
                                    pricing_id: form.querySelector('[name="pricing_id"]') ? form.querySelector('[name="pricing_id"]').value : null,
                                    username: form.querySelector('[name="username"]') ? form.querySelector('[name="username"]').value : null,
                                    user_id: form.querySelector('[name="user_id"]') ? form.querySelector('[name="user_id"]').value : null,
                                    email: form.querySelector('[name="email"]') ? form.querySelector('[name="email"]').value : null,
                                    password: form.querySelector('[name="password"]') ? form.querySelector('[name="password"]').value : null,
                                    tel: form.querySelector('[name="phone_number"]') ? form.querySelector('[name="phone_number"]').value : null,
                                    ten_user: form.querySelector('[name="ten_user"]') ? form.querySelector('[name="ten_user"]').value : null,
                                    email_user: form.querySelector('[name="email_user"]') ? form.querySelector('[name="email_user"]').value : null,
                                    role: form.querySelector('[name="role"]') ? form.querySelector('[name="role"]').value : null,
                                    id: business_field_id ? business_field_id : null
                                },
                                success: function (data) {
                                    console.log(data);
                                    if (!data.status) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1, Swal.fire({
                                            text: data.meta,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        })
                                    } else {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1, Swal.fire({
                                            text: data.payload,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        }).then((function (modalForm) {
                                            modalForm.isConfirmed && modal.hide()
                                            window.location.reload()
                                        }))
                                    }
                                },
                                error: function (data) {
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
                modalForm.querySelector('[data-kt-business_field-modal-action="cancel"]').addEventListener("click", (modalForm => {
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
                })), modalForm.querySelector('[data-kt-business_field-modal-action="close"]').addEventListener("click", (modalForm => {
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
    KTbusiness_fieldAddbusiness_field.init()
}));
