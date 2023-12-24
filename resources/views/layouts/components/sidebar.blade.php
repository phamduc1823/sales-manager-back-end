<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{route('admin.home')}}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-dark.svg')}}"
                 class="w-100 app-sidebar-logo-default"/>
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-small.svg')}}"
                 class="w-100 app-sidebar-logo-minimize"/>
        </a>
        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                 data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                 data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                 data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                 data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                     data-kt-menu="true" data-kt-menu-expand="false">
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item show">
                        <!--begin:Menu link-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="{{ route('admin.home') }}">
												<span class="menu-icon">
													<i class="ki-duotone ki-element-11 text-primary fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
												</span>
                                <span class="menu-title">Bảng điều khiển</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Trang</span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    @foreach(\Menu::get() as $menu)
                        @hasanyrole($menu['role'])
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            @if (isset($menu['link']))
                                <a href="{{ route($menu['link']). $menu['param'] }}">
                    <span class="menu-link {{url()->full() == route($menu['link']). $menu['param'] ? 'active' : ''}}">
                            <span class="menu-icon">
                                {!! $menu['icon'] !!}
                            </span>
                            <span class="menu-title">{!! $menu['name'] !!}</span>
                            @if (isset($menu['subs-menu']))
                            <span class="menu-arrow"></span>
                        @endif
                        </span>
                                </a>
                            @else
                                <span class="menu-link">
                        <span class="menu-icon">
                            {!! $menu['icon'] !!}
                        </span>
                        <span class="menu-title">{!! $menu['name'] !!}</span>
                        <span class="menu-arrow"></span>
                    </span>
                            @endif
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                @if (isset($menu['subs-menu']))
                                    @foreach($menu['subs-menu'] as $subMenu)
                                        @hasanyrole($subMenu['role'])
                                        <div class="menu-item">
                                            <a class="menu-link {{url()->full() == route($subMenu['link']). $subMenu['param'] ? 'active' : ''}}"
                                               href="{{ route($subMenu['link']). $subMenu['param'] }}"
                                            >
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                                <span class="menu-title">{{ $subMenu['name'] }}</span>
                                            </a>
                                        </div>
                                        @endhasrole
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endhasrole
                    @endforeach
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
</div>
<!--end::Sidebar-->
