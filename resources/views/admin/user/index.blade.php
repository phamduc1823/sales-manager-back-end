@extends('layouts.admin')
@section('title','Danh sách người dùng')
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
                    <input type="text" data-kt-business_field-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm"/>
                </div>
                <!--end::Search-->

            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">

                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button type="button" class="btn btn-primary" style="margin-right: 20px" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_business_field">
                        <i class="ki-duotone ki-plus fs-2"></i>Thêm Người Dùng
                    </button>
                    <!--begin::Hide default export buttons-->
                    <div id="kt_datatable_business_field_buttons" class="d-none"></div>
                    <!--end::Hide default export buttons-->
                    <!--end::Export-->
                    <!--begin::Add user-->

                    <button type="button" id="show-trash" class="btn btn-danger">
                        Danh sách đã xoá
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->
                <!--begin::Group actions-->
                <!--end::Modal - New Card-->
                <!--begin::Modal - Add task-->
                <div class="modal fade" id="kt_modal_add_business_field" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_add_business_field_header">
                                <!--begin::Modal title-->
                                <h2 class="modal-header_title fw-bold">Thêm người dùng</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                     data-kt-business_field-modal-action="close">
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
                                <form id="kt_modal_add_business_field_form" class="form" action="#">
                                    <!--begin::Scroll-->
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10"
                                         id="kt_modal_add_business_field_scroll" data-kt-scroll="true"
                                         data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                         data-kt-scroll-dependencies="#kt_modal_add_business_field_header"
                                         data-kt-scroll-wrappers="#kt_modal_add_business_field_scroll"
                                         data-kt-scroll-offset="300px">
                                        @csrf
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Tên user</label>
                                            <input type="text" name="ten_user"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   value=""/>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                                            <input type="text" name="email_user"
                                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                                   value=""/>
                                        </div>

                                        <div class="form-add-user">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Mật khẩu</label>
                                                <input type="password" name="password" autocomplete="off"
                                                       class="form-control form-control-solid"/>
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="fw-semibold fs-6 mb-2">Số điện thoại</label>
                                                <input class="form-control form-control-solid" name="phone_number"
                                                       id="kt_inputmask_2" inputmode="text">
                                            </div>
                                            <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Quyền hạn</label>
                                            <select name="role" class="form-select form-select-solid"
                                                     data-placeholder="Chọn quyền hạn">
                                               @foreach($role as $rl)
                                                <option value="{{$rl->id}}">{{$rl->name}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                        </div>
                                        <div class="show-tenant">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tên chi nhánh</label>
                                                <input type="text" name="ten_chi_nhanh"
                                                       class="form-control form-control-solid mb-3 mb-lg-0"
                                                       value=""/>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Lĩnh vực kinh
                                                    doanh</label>
                                                <input type="text" name="linh_vuc_kinh_doanh"
                                                       class="form-control form-control-solid mb-3 mb-lg-0"
                                                       value=""/>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Trạng thái</label>
                                                <input type="text" name="trang_thai"
                                                       class="form-control form-control-solid mb-3 mb-lg-0"
                                                       value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Actions-->
                                    <div class="text-center pt-10">
                                        <button type="reset" class="btn btn-light me-3"
                                                data-kt-business_field-modal-action="cancel">Huỷ
                                        </button>
                                        <button type="submit" id="button-submit" class="btn btn-primary btn-submit"
                                                data-kt-business_field-modal-action="submit">
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
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_business_field">
                <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Tên user</th>
                    <th class="min-w-125px">Email</th>
                    <th class="min-w-125px">Ngày tạo</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                @foreach($users as $user)
                    <tr data-id="{{$user->id}}">
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{date_format($user->created_at,'d-m-Y')}}</td>
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
                                       data-bs-target="#kt_modal_add_business_field" data-id="{{$user->id}}">Chi tiết</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3" data-bs-toggle="modal"
                                       data-bs-target="#kt_modal_add_business_field"
                                       data-edit-id="{{$user->id}}">Sửa</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3"
                                       data-kt-business_field-table-filter="delete_row"
                                       data-id="{{$user->id}}">Xoá</a>
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
    <script src="{{asset('assets/js/custom/apps/business-fields/list/table.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/business-fields/list/export-business_field.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/business-fields/list/add.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(".form-add-user").hide()
            $('#kt_modal_add_business_field').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget)
                    let tenant_id = button.data('id')
                    let edit_id = button.data('edit-id');
                    let ten_chi_nhanh = $('#kt_modal_add_business_field_form input[name="ten_chi_nhanh"]')
                    let ten_user = $('#kt_modal_add_business_field_form input[name="ten_user"]')
                    let email = $('#kt_modal_add_business_field_form input[name="email_user"]')
                    let linh_vuc_kinh_doanh = $('#kt_modal_add_business_field_form input[name="linh_vuc_kinh_doanh"]')
                    let trang_thai = $('#kt_modal_add_business_field_form input[name="trang_thai"]')
                    let phone_number = $('#kt_modal_add_business_field_form input[name="phone_number"]')
                    let role = $('#kt_modal_add_business_field_form select[name="role"]')
                    if (edit_id) {
                        $("#button-submit").show()
                        $(".form-add-user").show()
                        $(".show-tenant").hide()
                        KTApp.showPageLoading();
                        $.ajax({
                            url: '{{route('admin.user.show')}}',
                            type: 'GET',
                            data: {update_id: edit_id},
                            success: function (data) {
                                console.log(data)
                                KTApp.hidePageLoading();
                                $('#kt_modal_add_business_field_header .modal-header_title').text('Cập nhật người dùng')
                                if (data.status) {
                                    $('#kt_modal_add_business_field_form').append('<input type="hidden" name="id" value="' + data.payload.id + '">')
                                    ten_user.val(data.payload.name)
                                    email.val(data.payload.email)
                                    phone_number.val(data.payload.tel ?? data.payload.tel)
                                    role.val(data.payload.role).prop('selected', true);
                                    $("form").find("input, select, textarea").prop("disabled", false);
                                } else {
                                    $('#kt_modal_add_business_field_form input[name="id"]').val(null)
                                    ten_user.val('')
                                    email.val('')
                                    phone_number.val('')
                                    role.val('')
                                }
                            }
                        })
                    }
                    if (tenant_id) {
                        KTApp.showPageLoading();
                        $.ajax({
                            url: '{{route('admin.user.show')}}',
                            type: 'GET',
                            data: {show_id: tenant_id},
                            success: function (data) {
                                $("#button-submit").hide()
                                $(".form-add-user").hide()
                                $(".show-tenant").show()
                                KTApp.hidePageLoading();
                                $('#kt_modal_add_business_field_header .modal-header_title').text('Chi tiết người dùng')
                                if (data.status) {
                                    let tenantData = [];
                                    let locationData = [];
                                    let statusData = [];
                                    $('#kt_modal_add_business_field_form').append('<input type="hidden" name="id" value="' + data.payload.id + '">')
                                    if (data.payload.tenants.length !== 0) {
                                        data.payload.tenants.forEach(function (value) {
                                            tenantData.push(value.name)
                                            statusData.push(value.name === 0 ? 'Chưa kích hoạt' : 'Kích hoạt')
                                            locationData.push(value.business_field.name)
                                        })
                                    }
                                    ten_user.val(data.payload.name)
                                    ten_chi_nhanh.val(tenantData.join(", ") ? tenantData.join(", ") : "")
                                    email.val(data.payload.email)
                                    linh_vuc_kinh_doanh.val(locationData.join(", ") ? locationData.join(", ") : "")
                                    trang_thai.val(statusData.join(", ") ? statusData.join(", ") : "")
                                    $("form").find("input, select, textarea").prop("disabled", true);
                                } else {
                                    $('#kt_modal_add_business_field_form input[name="id"]').val(null)
                                    ten_chi_nhanh.val('')
                                    ten_user.val('')
                                    email.val('')
                                    linh_vuc_kinh_doanh.val('')
                                    trang_thai.val('')
                                    $("form").find("input, select, textarea").prop("disabled", false);
                                }
                            }
                        })
                    }
                    if(!tenant_id && !edit_id){
                        $('#kt_modal_add_business_field_form input[name="id"]').val(null)
                        $('#kt_modal_add_business_field_header .modal-header_title').text('Thêm mới người dùng')
                        $("form").find("input, select, textarea").prop("disabled", false);
                        $("#button-submit").show()
                        $(".form-add-user").show()
                        $(".show-tenant").hide()
                        ten_chi_nhanh.val('')
                        ten_user.val('')
                        email.val('')
                        linh_vuc_kinh_doanh.val('')
                        trang_thai.val('')
                        phone_number.val('')
                    }
                }
            )
            $('#show-trash').click(function () {
                window.location.href = '{{route('admin.user.trash')}}'
            })
        })
    </script>
@endpush
