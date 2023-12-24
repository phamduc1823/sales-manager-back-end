$(document).ready(function () {
    "use strict";
    var KTSigninGeneral = function () {
        var t, e, r;
        return {
            init: function () {
                t = document.querySelector("#kt_sign_in_form"), e = document.querySelector("#kt_sign_in_submit"), r = FormValidation.formValidation(t, {
                    fields: {
                        email: {
                            validators: {
                                regexp: {
                                    regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                    message: "Vui lòng nhập đúng định dạng email"
                                }, notEmpty: {message: "Email is required"}
                            }
                        }, password: {validators: {notEmpty: {message: "Mât khẩu không được để trống"}}}
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                }), !function (t) {
                    try {
                        return new URL(t), !0
                    } catch (t) {
                        return !1
                    }
                }(e.closest("form").getAttribute("action")) ? e.addEventListener("click", (function (i) {
                    i.preventDefault(), r.validate().then((function (r) {
                        "Valid" == r ? (e.setAttribute("data-kt-indicator", "on"), e.disabled = !0, setTimeout((function () {
                            $.ajax({
                                method: 'post',
                                url: '/login',
                                data:
                                    {
                                        _token: $('input[name="_token"]').val(),
                                        email: $('input[name="email"]').val(),
                                        password: $('input[name="password"]').val()
                                    },
                                success: function (response) {
                                    if (response === 'success') {
                                        t.querySelector('[name="email"]').value = "", t.querySelector('[name="password"]').value = "";
                                        var r = t.getAttribute("data-kt-redirect-url");
                                        r && (location.href = r)
                                    } else {
                                        Swal.fire({
                                            text: "Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại!",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        }).then((() => {
                                            e.removeAttribute("data-kt-indicator"), e.disabled = !1
                                        }))
                                    }
                                },
                                error: function (e) {
                                    console.error(e);
                                }
                            })

                        }), 2e3)) : Swal.fire({
                            text: "Đã xảy ra lỗi. Vui lòng thử lại!",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                })) : e.addEventListener("click", (function (i) {
                    i.preventDefault(), r.validate().then((function (r) {
                        "Valid" == r ? (e.setAttribute("data-kt-indicator", "on"), e.disabled = !0, axios.post(e.closest("form").getAttribute("action"), new FormData(t)).then((function (e) {
                            if (e) {
                                t.reset(), Swal.fire({
                                    text: "Đăng nhập thành công!",
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok!",
                                    customClass: {confirmButton: "btn btn-primary"}
                                });
                                const e = t.getAttribute("data-kt-redirect-url");
                                e && (location.href = e)
                            } else Swal.fire({
                                text: "Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại!",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok!",
                                customClass: {confirmButton: "btn btn-primary"}
                            })
                        })).catch((function (t) {
                            Swal.fire({
                                text: "Đã xảy ra lỗi. Vui lòng thử lại!",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok!",
                                customClass: {confirmButton: "btn btn-primary"}
                            })
                        })).then((() => {
                            e.removeAttribute("data-kt-indicator"), e.disabled = !1
                        }))) : Swal.fire({
                            text: "Đã xảy ra lỗi. Vui lòng thử lại!",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }))
            }
        }
    }();
    KTUtil.onDOMContentLoaded((function () {
        KTSigninGeneral.init()
    }));

})
