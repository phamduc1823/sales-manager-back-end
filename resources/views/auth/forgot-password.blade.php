@extends('layouts.auth')
@section('title','Quên mật khẩu')
@section('content')
    <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" data-kt-redirect-url="" action="#">
        <!--begin::Heading-->
        @csrf
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">Quên mật khẩu ?</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-500 fw-semibold fs-6">Vui lòng nhập email của bạn để đặt lại mật khẩu.</div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" />
            <!--end::Email-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="button" id="kt_password_reset_submit" class="btn btn-primary me-4">
                <!--begin::Indicator label-->
                <span class="indicator-label">Xác nhận</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress">Vui lòng đợi...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                <!--end::Indicator progress-->
            </button>
            <a href="{{ route('login') }}" class="btn btn-light">Huỷ</a>
        </div>
        <!--end::Actions-->
    </form>

@endsection
