@extends('layouts.admin')
@section('title','Bảng điều khiển')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
                        <!--begin::Card widget 4-->
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">
                                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$reponse['user']}}</span>
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Subtitle-->
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Người dùng</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <!--begin::Labels-->
                                <div class="d-flex flex-column content-justify-center w-100">
                                    <div id="kt_apexcharts_2" class="w-100 h-300px"></div>
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 4-->
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">
                                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$reponse['tenant']}}</span>
                                        <!--end::Amount-->
                                    </div>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Cửa hàng</span>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex align-items-end px-0 pb-0">
                                <!--begin::Chart-->
                                <div id="kt_card_widget_6_chart" class="w-100 h-300px"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
                        <div class="card card-flush h-md-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5 mb-6">
                                <h3 class="card-title align-items-start flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{number_format($reponse['orderDay'])}}</span>
                                        <span class="fs-3 fw-semibold text-gray-400 align-self-start me-1">VNĐ</span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-400">Doanh thu hôm nay</span>
                                    <div class="d-flex align-items-center mb-2 my-2">
                                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{number_format($reponse['orderMonth'])}}</span>
                                        <span class="fs-3 fw-semibold text-gray-400 align-self-start me-1">VNĐ</span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-400">Doanh thu tháng {{$reponse['month']}}</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-0 px-0">
                                <!--begin::Nav-->
                                <ul class="nav d-flex justify-content-between mb-3 mx-9">
                                    <!--begin::Item-->
                                    <li class="nav-item mb-4">
                                        <!--begin::Link-->
                                        <a class="nav-link btn btn-flex flex-center btn-active-danger btn-color-gray-600 btn-active-color-white rounded-2 w-150px h-35px active" data-bs-toggle="tab" id="kt_charts_widget_35_tab_1" href="#kt_charts_widget_35_tab_content_1">1 Tuần</a>
                                        <!--end::Link-->
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <li class="nav-item mb-4">
                                        <!--begin::Link-->
                                        <a class="nav-link btn btn-flex flex-center btn-active-danger btn-color-gray-600 btn-active-color-white rounded-2 w-150px h-35px" data-bs-toggle="tab" id="kt_charts_widget_35_tab_2" href="#kt_charts_widget_35_tab_content_2">1 Tháng</a>
                                        <!--end::Link-->
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <li class="nav-item mb-4">
                                        <!--begin::Link-->
                                        <a class="nav-link btn btn-flex flex-center btn-active-danger btn-color-gray-600 btn-active-color-white rounded-2 w-150px h-35px" data-bs-toggle="tab" id="kt_charts_widget_35_tab_3" href="#kt_charts_widget_35_tab_content_3">1 Năm</a>
                                        <!--end::Link-->
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                                <!--begin::Tab Content-->
                                <div class="tab-content mt-n6">
                                    <!--begin::Tap pane-->
                                    <div class="tab-pane fade active show" id="kt_charts_widget_35_tab_content_1">
                                        <!--begin::Chart-->
                                        <div id="kt_charts_widget_35_chart_1" data-kt-chart-color="primary" class="min-h-auto h-550px ps-3 pe-6"></div>
                                        <!--end::Chart-->
                                    </div>
                                    <!--end::Tap pane-->
                                    <!--begin::Tap pane-->
                                    <div class="tab-pane fade" id="kt_charts_widget_35_tab_content_2">
                                        <!--begin::Chart-->
                                        <div id="kt_charts_widget_35_chart_2" data-kt-chart-color="primary" class="min-h-auto h-350px ps-3 pe-6"></div>
                                        <!--end::Chart-->
                                    </div>
                                    <!--end::Tap pane-->
                                    <!--begin::Tap pane-->
                                    <div class="tab-pane fade" id="kt_charts_widget_35_tab_content_3">
                                        <!--begin::Chart-->
                                        <div id="kt_charts_widget_35_chart_3" data-kt-chart-color="primary" class="min-h-auto  ps-3 pe-6"></div>
                                        <!--end::Chart-->
                                    </div>
                                    <!--end::Tap pane-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Body-->
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/custom/apps/statistic/main.js')}}"></script>
@endpush
