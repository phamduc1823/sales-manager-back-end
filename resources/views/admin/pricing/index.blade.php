@extends('layouts.admin')
@section('title','Danh sách bảng giá')
@section('content')
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-pricing-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm"/>
                </div>
                <!--end::Search-->

            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Export-->
                    <!--begin::Hide default export buttons-->
                    <div id="kt_datatable_pricing_buttons" class="d-none"></div>
                    <!--end::Hide default export buttons-->
                    <!--end::Export-->
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_pricing">
                        <i class="ki-duotone ki-plus fs-2"></i>Thêm bảng giá
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->
                <!--begin::Group actions-->

                <!--end::Modal - New Card-->
                <!--begin::Modal - Add task-->
                <div class="modal fade" id="kt_modal_add_pricing" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_add_pricing_header">
                                <!--begin::Modal title-->
                                <h2 class="modal-header_title fw-bold">Thêm bảng giá</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                     data-kt-pricing-modal-action="close">
                                    <i class="ki-duotone ki-cross fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body px-5 my-7">
                                <!--begin::Form-->
                                <form id="kt_modal_add_pricing_form" class="form" action="#">
                                    <!--begin::Scroll-->
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10"
                                         id="kt_modal_add_pricing_scroll" data-kt-scroll="true"
                                         data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                         data-kt-scroll-dependencies="#kt_modal_add_pricing_header"
                                         data-kt-scroll-wrappers="#kt_modal_add_pricing_scroll"
                                         data-kt-scroll-offset="300px">
                                        @csrf
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Tên gói dịch vụ</label>
                                            <input type="text" name="name"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   placeholder="Tên gói dịch vụ" value=""/>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Số chi nhánh tối đa</label>
                                            <input type="number" name="max_locations"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   placeholder="Số chi nhánh tối đa" value=""/>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Số người dùng tối đa</label>
                                            <input type="number" name="max_users"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   placeholder="Số chi nhánh tối đa" value=""/>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Giá gói (VNĐ)</label>
                                            <input type="number" name="price"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   placeholder="Giá gói" value=""/>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Số ngày sử dụng</label>
                                            <input type="number" name="expiry_day"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   placeholder="Số ngày sử dụng" value=""/>
                                        </div>
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Actions-->
                                    <div class="text-center pt-10">
                                        <button type="reset" class="btn btn-light me-3"
                                                data-kt-pricing-modal-action="cancel">Huỷ
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-submit"
                                                data-kt-pricing-modal-action="submit">
                                            <span class="indicator-label">Lưu</span>
                                            <span class="indicator-progress">Vui lòng đợi...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Modal body-->
                        </div>
                        <!--end::Modal content-->
                    </div>
                    <!--end::Modal dialog-->
                </div>
                <!--end::Modal - Add task-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_pricing">
                <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Tên gói dịch vụ</th>
                    <th class="min-w-125px">Số chi nhánh tối đa</th>
                    <th class="min-w-125px">Số người dùng tối đa</th>
                    <th class="min-w-125px">Giá (VNĐ)</th>
                    <th class="min-w-125px">Số ngày sử dụng</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                @foreach($pricings as $pricing)
                    <tr data-id="{{$pricing->id}}">
                        <td>{{$pricing->name}}</td>
                        <td>{{$pricing->max_locations}}</td>
                        <td>{{$pricing->max_users}}</td>
                        <td>{{number_format($pricing->price)}}</td>
                        <td>{{$pricing->expiry_day}}</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                               data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div
                                class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3" data-bs-toggle="modal"
                                       data-bs-target="#kt_modal_add_pricing" data-id="{{$pricing->id}}">Sửa</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3"
                                       data-kt-pricing-table-filter="delete_row" data-id="{{$pricing->id}}">Xoá</a>
                                </div>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--begin::Page loading(append to body)-->
    <div class="page-loader flex-column bg-dark bg-opacity-25">
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Đang tải...</span>
    </div>
    <!--end::Page loading-->
@endsection
@push('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{asset('assets/js/custom/apps/pricings/list/table.js')}}"></script>
{{--    <script src="{{asset('assets/js/custom/apps/pricings/list/export-pricing.js')}}"></script>--}}
    <script src="{{asset('assets/js/custom/apps/pricings/list/add.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#kt_modal_add_pricing').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let pricing_id = button.data('id')
                let name = $('#kt_modal_add_pricing_form input[name="name"]')
                let max_locations = $('#kt_modal_add_pricing_form input[name="max_locations"]')
                let max_users = $('#kt_modal_add_pricing_form input[name="max_users"]')
                let price = $('#kt_modal_add_pricing_form input[name="price"]')
                let expiry_day = $('#kt_modal_add_pricing_form input[name="expiry_day"]')
                if (pricing_id) {
                    KTApp.showPageLoading();
                    $.ajax({
                        url: '{{route('admin.pricing.show')}}',
                        type: 'GET',
                        data: {id: pricing_id},
                        success: function (data) {
                            KTApp.hidePageLoading();
                            $('#kt_modal_add_pricing_header .modal-header_title').text('Cập nhật bảng giá')
                            $('#kt_modal_add_pricing_form').append('<input type="hidden" name="id" value="' + data.payload.id + '">')
                            name.val(data.payload.name)
                            max_locations.val(data.payload.max_locations)
                            max_users.val(data.payload.max_users)
                            price.val(data.payload.price)
                            expiry_day.val(data.payload.expiry_day)
                        }
                    })
                } else {
                    $('#kt_modal_add_pricing_form input[name="id"]').val('')
                    $('#kt_modal_add_pricing_header .modal-header_title').text('Thêm mới bảng giá')
                    name.val('')
                    max_locations.val('')
                    max_users.val('')
                    price.val('')
                    expiry_day.val('')
                }
            })
        })
    </script>
@endpush
