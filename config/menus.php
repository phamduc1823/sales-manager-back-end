<?php
$ROLE_SUPER_ADMIN = 'super-admin';
$ROLE_HAS_ADMINS = 'admin|super-admin';
return [
    [
        "icon" => '<i class="las fs-2x text-primary la-barcode"></i>',
        "name" => "Lĩnh vực kinh doanh",
        "role" => $ROLE_SUPER_ADMIN,
        "subs-menu" => [
            [
                "name" => "DS Lĩnh vực kinh doanh",
                "param" => '',
                "link" => "admin.bf.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
            [
                "name" => "DS Loại dữ liệu mẫu",
                "param" => '',
                "link" => "admin.seed.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
            [
                "name" => "DS Dữ liệu mẫu",
                "param" => '',
                "link" => "admin.data-seed.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
        ]
    ],
    [
        "icon" => '<i class="las fs-2x text-primary la-money-bill"></i>',
        "name" => "Bảng giá",
        "role" => $ROLE_SUPER_ADMIN,
        "subs-menu" => [
            [
                "name" => "Danh sách bảng giá",
                "param" => '',
                "link" => "admin.pricing.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
        ]
    ],
    [
        "icon" => '<i class="las fs-2x text-primary la-user-circle"></i>',
        "name" => "Người dùng",
        "role" => $ROLE_SUPER_ADMIN,
        "subs-menu" => [
            [
                "name" => "Danh sách người dùng",
                "param" => '',
                "link" => "admin.user.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
            [
                "name" => "Danh sách cửa hàng",
                "param" => '',
                "link" => "admin.tenant.index",
                "role" => $ROLE_SUPER_ADMIN
            ],
        ]
    ],
    [
        "icon" => '<i class="las fs-2x text-primary la-clipboard-list"></i>',
        "name" => "Đơn hàng",
        "role" => $ROLE_HAS_ADMINS,
        "subs-menu" => [
            [
                "name" => "Danh sách liên hệ",
                "param" => '',
                "link" => "admin.order.index",
                "role" => $ROLE_HAS_ADMINS
            ],
            [
                "name" => "Danh sách yêu cầu xử lý",
                "param" => '',
                "link" => "admin.order.request",
                "role" => $ROLE_HAS_ADMINS
            ],
        ]
    ],
];
