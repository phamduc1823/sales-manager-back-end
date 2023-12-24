@extends('layouts.auth')
@section('title','Đăng nhập | NextGo')
@section('content')
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{route('admin.home')}}" action="#">
        <!--begin::Heading-->
        @csrf
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">Đăng nhập</h1>
            <!--end::Title-->
        </div>
        <!--begin::Heading-->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" />
            <!--end::Email-->
        </div>
        <!--end::Input group=-->
        <div class="fv-row mb-3">
            <!--begin::Password-->
            <input type="password" placeholder="Mật khẩu" name="password" autocomplete="off" class="form-control bg-transparent"/>
            <!--end::Password-->
        </div>
        <!--end::Input group=-->
        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            <!--begin::Link-->
            <a href="{{ route('forgot-password') }}" class="link-primary">Quên mật khẩu ?</a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Đăng nhập</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress">Vui lòng đợi...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                <!--end::Indicator progress-->
            </button>
        </div>
        <!--end::Submit button-->
        <!--begin::Sign up-->
{{--        <div class="text-gray-500 text-center fw-semibold fs-6">Bạn chưa có tài khoản ?--}}
{{--            <a href="{{ route('register') }}" class="link-primary">Đăng kí</a></div>--}}
        <!--end::Sign up-->
    </form>
@endsection
