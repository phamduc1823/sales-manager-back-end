@extends('layouts.admin')
@section('title','Danh sách dữ liệu mẫu')
@section('content')
    <!--begin::Card-->
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
                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    <!--begin::Filter-->
                    <div class="w-250px me-3">
                        <!--begin::Select2-->
                        <select class="form-select form-select-solid" data-control="select2"  data-placeholder="Ngành hàng" data-kt-ecommerce-order-filter="business_field_name">
                            <option></option>
                            <option value="all">Tất cả</option>
                            @foreach($dataBusinessFields as $dataBusinessField)
                            <option value="{{$dataBusinessField->code}}">{{$dataBusinessField->name}}</option>
                            @endforeach
                        </select>
                        <!--end::Select2-->
                    </div>
                    <div class="w-200px me-3">
                        <!--begin::Select2-->
                        <select class="form-select form-select-solid" data-control="select2"  data-placeholder="Kiểu loại dữ liệu" data-kt-ecommerce-order-filter="seed_type">
                            <option></option>
                            <option value="all">Tất cả</option>
                            <option value="Danh mục">Danh mục</option>
                            <option value="Thương hiệu">Thương hiệu</option>
                            <option value="Đơn vị hạng mục">Đơn vị hạng mục</option>
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--end::Filter-->
                    <!--begin::Add customer-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Thêm dữ liệu mẫu</button>
                    <!--end::Add customer-->
                </div>
                <!--end::Toolbar-->
                <!--begin::Group actions-->
                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
                    <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
                </div>
                <!--end::Group actions-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-25px">#</th>
                    <th class="min-w-125px">Tên ngành hàng</th>
                    <th class="min-w-125px">Mã ngành hàng</th>
                    <th class="min-w-125px">Tên loại dữ liệu</th>
                    <th class="min-w-125px">Kiểu loại dữ liệu</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                @foreach($dataSeeds as $item => $dataSeed)
                <tr data-id="{{$dataSeed['id']}}">
                    <td>
                        {{$item+1}}
                    </td>
                    <td>
                       {{$dataSeed['business_field_name']}}
                    </td>
                    <td>
                        {{$dataSeed['business_field_code']}}
                    </td>
                    <td>{{$dataSeed['seed_name']}}</td>
                    <td> {{$dataSeed['seed_type']==0?'Danh mục':($dataSeed['seed_type']==1?'Thương hiệu':'Đơn vị hạng mục')}}</td>
                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="" class="menu-link px-3" data-bs-toggle="modal"
                                   data-bs-target="#kt_modal_add_customer" data-id="{{$dataSeed['id']}}">Sửa</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row" data-id="{{$dataSeed['id']}}">Xoá</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </td>
                </tr>
                @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    <!--begin::Modals-->
    <!--begin::Modal - Customers - Add-->
    <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="kt_modal_add_customer_form">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold modal-header_title">Thêm dữ liệu mẫu</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->
                            @csrf
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Ngành hàng</label>
                                <select class="required form-select" data-control="select2" name="business_field_id"
                                        data-placeholder="Chọn ngành hàng">
                                    <option class="option-hidden"></option>
                                    @foreach($dataBusinessFields as $dataBusinessField)
                                        <option value="{{$dataBusinessField->id}}">{{$dataBusinessField->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Loại dữ liệu mẫu</label>
                                <select class="required form-select" data-control="select2" name="seed_id"
                                        data-placeholder="Chọn loại dữ liệu mẫu">
                                    <option class="option-hidden"></option>
                                    @foreach($seeds as $seed)
                                        <option value="{{$seed->id}}">{{$seed->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Huỷ</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Lưu</span>
                            <span class="indicator-progress">Please wait...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
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
    <script src="{{asset('assets/js/custom/apps/data-seed/list/table.js')}}"></script>
    <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>
{{--    <script src="{{asset('assets/js/custom/apps/seed/list/export-seed.js')}}"></script>--}}
    <script src="{{asset('assets/js/custom/apps/data-seed/list/add.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#kt_modal_add_customer').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let business_field_seeds_id = button.data('id')
                let business_field_id = $('#kt_modal_add_customer_form select[name="business_field_id"]')
                let seed_id = $('#kt_modal_add_customer_form select[name="seed_id"]')
                if (business_field_seeds_id) {
                    KTApp.showPageLoading();
                    $.ajax({
                        url: '{{route('admin.data-seed.show')}}',
                        type: 'GET',
                        data: {id: business_field_seeds_id},
                        success: function (data) {
                            KTApp.hidePageLoading();
                            $('#kt_modal_add_customer_header .modal-header_title').text('Cập nhật dữ liệu mẫu')
                            $('#kt_modal_add_customer_form').append('<input type="hidden" name="id" value="' + data.payload.id + '">')
                            business_field_id.val(data.payload.business_field_id).prop('selected', true);
                            seed_id.val(data.payload.seed_id).prop('selected', true);
                        }
                    })
                } else {
                    $('#kt_modal_add_seed_form input[name="id"]').val('')
                    $('#kt_modal_add_seed_header .modal-header_title').text('Thêm mới dữ liệu mẫu')
                    business_field_id.val('')
                    seed_id.val('')
                }
            })
        })
    </script>
@endpush
