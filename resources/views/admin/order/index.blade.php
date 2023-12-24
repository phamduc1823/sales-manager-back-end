@extends('layouts.admin')
@section('title','Danh sách liên hệ')
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
                    <input type="text" data-kt-customer-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm"/>
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
                        <select class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Loại yêu cầu" data-kt-ecommerce-order-filter="type">
                            <option></option>
                            <option value="all">Tất cả</option>
                            <option value="Gia hạn">Gia hạn</option>
                            <option value="Thay đổi gói">Thay đổi gói</option>
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--end::Filter-->
                    <!--begin::Add customer-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_customer">Thêm bản ghi mới
                    </button>
                    <!--end::Add customer-->
                </div>
                <!--end::Toolbar-->
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
                    <th class="min-w-125px">Tên cửa hàng</th>
                    <th class="min-w-125px">Tên gói</th>
                    <th class="min-w-125px">Hạn gói hiện tại</th>
                    <th class="min-w-125px">Loại yêu cầu</th>
                    <th class="min-w-125px">Họ tên</th>
                    <th class="min-w-125px">SĐT liên hệ</th>
                    <th class="min-w-125px">Trạng thái</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                @foreach($subscriptionOrder as $item => $value)
                    <tr data-id="{{$value['id']}}">
                        <td>{{$item+1}}</td>
                        <td>{{$value['tenant']}}</td>
                        <td>{{$value['pricing']}}</td>
                        <td>{{$value['due_at']}}</td>
                        <td>{{$value['type']==0?'Thay đổi gói':'Gia hạn'}}</td>
                        <td>{{$value['name']}}</td>
                        <td>{{$value['tel']}}</td>
                        <td>
                            {{--                            <span class="badge status-list--span badge-light-{{$value['status']==0?'danger':($value['status']==1?'warning':($value['status']==2?'info':'success'))}}">{{$value['status']==0?'Huỷ':($value['status']==1?'Chưa xử lý':($value['status']==2?'Đang xử lý':'Đã hoàn thành'))}}</span>--}}
                            <select class="form-select form-select-solid status-list" aria-label="Trạng thái" {{$value['status']==3?'disabled':($value['status']==0?'disabled':'')}} >
                                <option value="0" {{$value['status']==0?'selected':''}}>Huỷ</option>
                                <option value="1" {{$value['status']==1?'selected':''}}>Chưa xử lý</option>
                                <option value="2" {{$value['status']==2?'selected':''}}>Đang xử lý</option>
                                <option value="3" {{$value['status']==3?'selected':''}}>Đã hoàn thành</option>
                            </select>
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                               data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div
                                class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3" data-bs-toggle="modal"
                                       data-bs-target="#kt_modal_show_detail" data-id="{{$value['id']}}">Chi tiết</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3" data-bs-toggle="modal"
                                       data-bs-target="#kt_modal_add_note" data-id="{{$value['id']}}">Ghi chú</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row"
                                       data-id="{{$value['id']}}">Xoá</a>
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
                        <h2 class="fw-bold modal-header_title">Thêm dữ bản ghi mới</h2>
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                             data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                             data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->
                            @csrf
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên cửa hàng</label>
                                <select class="required form-select" data-control="select2" name="tenant"
                                        data-placeholder="Chọn ngành hàng"
                                        data-dropdown-parent="#kt_modal_add_customer">
                                    <option></option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{$tenant->id}}">{{$tenant->business_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên gói</label>
                                <select class="required form-select" data-control="select2" name="pricing"
                                        data-placeholder="Chọn loại dữ liệu mẫu"
                                        data-dropdown-parent="#kt_modal_add_customer">
                                    <option></option>
                                    @foreach($pricings as $pricing)
                                        <option value="{{$pricing->id}}">{{$pricing->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Loại yêu cầu</label>
                                <select class="required form-select" data-control="select2" name="type"
                                        data-placeholder="Chọn loại dữ liệu mẫu"
                                        data-dropdown-parent="#kt_modal_add_customer">
                                    <option></option>
                                    <option value="0">Thay đổi gói</option>
                                    <option value="1">Gia hạn</option>
                                </select>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên khách hàng</label>
                                <input type="text" class="form-control form-control-solid" name="name"
                                       placeholder="Tên khách hàng"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Số điện thoại liên hệ</label>
                                <input type="text" class="form-control form-control-solid" name="tel"
                                       placeholder="Số điện thoại liên hệ"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Ghi chú</label>
                                <textarea class="form-control form-control-solid" name="note" id="" cols="30"
                                          rows="10"></textarea>
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
                            <span class="indicator-progress">Vui lòng đợi...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_show_detail" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="kt_modal_show_detail_form">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_show_detail_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold modal-header_title">Chi tiết liên hệ</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_show_detail_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_show_detail_scroll" data-kt-scroll="true"
                             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                             data-kt-scroll-dependencies="#kt_modal_show_detail_header"
                             data-kt-scroll-wrappers="#kt_modal_show_detail_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->
                            @csrf
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên cửa hàng</label>
                                <input type="text" disabled class="form-control form-control-solid tenant_name-detail"
                                       value="" placeholder="Tên cửa hàng"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên gói</label>
                                <input type="text" disabled class="form-control form-control-solid pricing_name-detail"
                                       value="" placeholder="Tên gói"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Loại yêu cầu</label>
                                <input type="text" disabled class="form-control form-control-solid type-detail" value=""
                                       placeholder="Loại yêu cầu"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tên khách hàng</label>
                                <input type="text" disabled class="form-control form-control-solid name-detail" value=""
                                       placeholder="Tên khách hàng"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Số điện thoại liên hệ</label>
                                <input type="text" disabled class="form-control form-control-solid tel-detail" value=""
                                       placeholder="Số điện thoại liên hệ"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Trạng thái</label>
                                <input type="text" disabled class="form-control form-control-solid status-detail"
                                       value="" placeholder="Trạng thái"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Người phụ trách</label>
                                <select class="form-select form-select-solid assigned_to-detail"
                                        data-control="select2" name="assigned_to"
                                        data-placeholder="Chọn người phụ trách"
                                        data-dropdown-parent="#kt_modal_show_detail" >
                                    <option></option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Ngày tạo</label>
                                <input type="text" disabled class="form-control form-control-solid created_at-detail"
                                       value="" placeholder="Ngày tạo"/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Ghi chú</label>
                                <textarea disabled class="form-control form-control-solid note-detail" name="" id=""
                                          cols="30" rows="10"></textarea>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_show_detail_cancel" class="btn btn-light me-3">Thoát</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        {{--                        <button type="submit" id="kt_modal_show_detail_submit" class="btn btn-primary">--}}
                        {{--                            <span class="indicator-label">Lưu</span>--}}
                        {{--                            <span class="indicator-progress">Vui lòng đợi...--}}
                        {{--														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>--}}
                        {{--                        </button>--}}
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_add_note" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="kt_modal_add_note_form">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_note_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold modal-header_title">Thêm ghi chú</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_add_note_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_note_scroll" data-kt-scroll="true"
                             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                             data-kt-scroll-dependencies="#kt_modal_add_note_header"
                             data-kt-scroll-wrappers="#kt_modal_add_note_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->
                            @csrf
                            <input type="hidden" name="subscription_order_id" value="">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Ghi chú</label>
                                <textarea class="required form-control" name="note" id="" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_note_cancel" class="btn btn-light me-3">Huỷ</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_note_submit" class="btn btn-primary">
                            <span class="indicator-label">Lưu</span>
                            <span class="indicator-progress">Vui lòng đợi...
														<span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
    <script src="{{asset('assets/js/custom/apps/order/list/table.js')}}"></script>
    <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/order/list/add.js')}}"></script>
    <script>
        $(document).ready(function () {
            //show detail
            $('#kt_modal_show_detail').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let subscription_order_id = button.data('id')
                let tenant_name = $('#kt_modal_show_detail_form .tenant_name-detail')
                let pricing_name = $('#kt_modal_show_detail_form .pricing_name-detail')
                let type = $('#kt_modal_show_detail_form .type-detail')
                let name = $('#kt_modal_show_detail_form .name-detail')
                let tel = $('#kt_modal_show_detail_form .tel-detail')
                let status = $('#kt_modal_show_detail_form .status-detail')
                let assigned_to = $('#kt_modal_show_detail_form .assigned_to-detail')
                let created_at = $('#kt_modal_show_detail_form .created_at-detail')
                let note = $('#kt_modal_show_detail_form .note-detail')
                $('#kt_modal_show_detail_form').append('<input type="hidden" name="subscription_order_id" value="' + subscription_order_id + '">')
                if (subscription_order_id) {
                    KTApp.showPageLoading();
                    $.ajax({
                        url: '{{route('admin.order.show')}}',
                        type: 'GET',
                        data: {id: subscription_order_id},
                        success: function (data) {
                            KTApp.hidePageLoading();
                            tenant_name.val(data.detail.tenant)
                            pricing_name.val(data.detail.pricing)
                            type.val(data.detail.type == 0 ? 'Thay đổi gói' : 'Gia hạn')
                            name.val(data.detail.name)
                            tel.val(data.detail.tel)
                            status.val(data.detail.status_name)
                            assigned_to.val(data.detail.assigned_to)
                            created_at.val(data.detail.created_at)
                            note.text(data.notes)
                        }
                    })
                }
            })
            //update status
            $('.status-list').on('change', function () {
                let status = $(this).val()
                let id = $(this).parents('tr').data('id')
                console.log(id, status)
                $.ajax({
                    url: '{{route('admin.order.update-status')}}',
                    type: 'PATCH',
                    data: {id: id, status: status, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        if (data.status == 200){
                            toastr.success(data.msg)
                            if (status == 3) {
                                $('.status-list').attr('disabled', true)
                            }
                        }
                    }
                })
            })

            $('.assigned_to-detail').on('change', function () {
                let assigned_to = $(this).val()
                let id = $('#kt_modal_show_detail_form input[name="subscription_order_id"]').val()
                $.ajax({
                    url: '{{route('admin.order.update-assigned')}}',
                    type: 'PATCH',
                    data: {id: id, assigned_to: assigned_to, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        if (data.status == 200) toastr.success('Cập nhật người phụ trách thành công')
                    }
                })
            })
            //add note
            $('#kt_modal_add_note').on('show.bs.modal', function (event) {
                let subscription_order_id = $(event.relatedTarget).data('id')
                $('#kt_modal_add_note_form input[name="subscription_order_id"]').val(subscription_order_id)
                $.ajax({
                    url: '{{route('admin.order.show-note')}}',
                    type: 'GET',
                    data: {subscription_order_id: subscription_order_id},
                    success: function (data) {
                        $('#kt_modal_add_note_form textarea[name="note"]').text(data.notes)
                    }
                })
            })
        })
    </script>
@endpush
