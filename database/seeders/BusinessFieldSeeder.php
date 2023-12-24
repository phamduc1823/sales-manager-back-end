<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessFieldSeeder extends Seeder
{
    /**
     * Run the database list.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_fields')->insert([
            [
                "code" => "GROCERY",
                "name" => "Tạp hóa & Siêu thị mini",
                "detail" => "Siêu thị, Cửa hàng tạp hóa, Cửa hàng tiện lợi, Siêu thị mini, Cửa hàng bán lẻ, Trung tâm thương mại, Khác..."
            ],
            [
                "code" => "INSURANCE",
                "name" => "DỊch vụ bảo hiểm",
                "detail" => "Công ty bảo hiểm, Đại lý bảo hiểm cá nhân, Đại lý bảo hiểm tổ chức, Khác..."
            ],
            [
                "code" => "MATERIALS",
                "name" => "Vật liệu & máy móc",
                "detail" => "Sản xuất cơ khí, Máy móc công nghiệp, Vật liệu xây dựng, Thiết kế, Lắp đặt thi công, Khác..."
            ],
            [
                "code" => "SERVICE",
                "name" => "Dịch vụ",
                "detail" => "Dịch vụ tài chính, Cho thuê nhà đất, Dịch vụ thuế, Cầm đồ, Dịch vụ tour..."
            ],
            [
                "code" => "FASHION",
                "name" => "Thời trang",
                "detail" => "Quần áo, Giày, Túi xách, Trang sức, Mũ nón, Đồng hồ, Kính mắt, Khác..."
            ],
            [
                "code" => "FNB",
                "name" => "Bar - Cafe & Nhà hàng, Karaoke",
                "detail" => "Khu vui chơi giải trí ăn uống, Karaoke, Club/bar/pub, Khác..."
            ],
            [
                "code" => "PHONE_ELECTRIC",
                "name" => "Điện thoại & Điện máy",
                "detail" => "Điện thoại, Máy tính, Điện máy, Đồ điện tử, Linh phụ kiện điện tử, Khác..."
            ],
            [
                "code" => "PHARMACY",
                "name" => "Nhà thuốc",
                "detail" => "Nhà thuốc, Phòng khám, Bệnh viện, Thiết bị y tế, Nha khoa, Khác"
            ],
            [
                "code" => "COSMETICS",
                "name" => "Mỹ phẩm",
                "detail" => "Nước hoa, Mỹ phẩm,..."
            ],
            [
                "code" => "GIFT",
                "name" => "Hoa & Quà tặng",
                "detail" => "Hoa, quà tặng..."
            ],
            [
                "code" => "VICTUALS",
                "name" => "Nông sản & Thực phẩm",
                "detail" => "Lúa, gạo, đồ thực phẩm, ăn uống..."
            ],
            [
                "code" => "BABY",
                "name" => "Mẹ & Bé",
                "detail" => "Đồ dùng mẹ và bé, quần áo, sửa, tã, bỉm, đồ chơi..."
            ],
            ["code" => "STATIONERY",
                "name" => "Sách & Văn phòng phẩm",
                "detail" => "Sách, vở, bút, đồ dùng văn phòng phẩm..."
            ], [
                "code" => "HOUSEHOLD",
                "name" => "Nội thất & Gia dụng",
                "detail" => "Bàn ghế, Chăn ga gối đệm, Thiết bị vệ sinh, Thiết bị nhà bếp, Đèn, Sàn gỗ, Gạch ốp lát, Cây cảnh, Cửa kính, Trang trí nội thất, Khác..."
            ],
            [
                "code" => "MACHINES",
                "name" => "Xe, Máy móc & Linh kiện",
                "detail" => "Ô tô, Xe máy, Xe điện, Xe đạp, Phụ tùng, Phụ kiện, Khác..."
            ],
            [
                "code" => "SPA",
                "name" => "Spa, salon & Thẩm mỹ viện",
                "detail" => "Spa, Thẩm mỹ viện, Salon tóc, Nail, Khác..."
            ],
            [
                "code" => "OTHER",
                "name" => "khác",
                "detail" => "Khác..."
            ]
        ]);
    }
}
