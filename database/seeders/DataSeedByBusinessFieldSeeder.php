<?php

namespace Database\Seeders;

use App\Models\BusinessField;
use App\Models\Seed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSeedByBusinessFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $businessFields = BusinessField::query()->get();
        $seeds = [
            [
                'name' => 'Quần',
                'type' => 0,
                'business_field_id' => collect([5]),
            ],
            [
                'name' => 'Mì tôm',
                'type' => 0,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Bảo hiểm ô tô',
                'type' => 0,
                'business_field_id' => collect([2]),
            ],
            [
                'name' => 'Dịch vụ thuê nhà',
                'type' => 0,
                'business_field_id' => collect([4]),
            ],
            [
                'name' => 'Kem dưỡng da',
                'type' => 0,
                'business_field_id' => collect([9]),
            ],
            [
                'name' => ' Hoa hồng',
                'type' => 0,
                'business_field_id' => collect([10]),
            ],
            [
                'name' => 'Gạo',
                'type' => 0,
                'business_field_id' => collect([11]),
            ],
            [
                'name' => 'Tiểu thuyết',
                'type' => 0,
                'business_field_id' => collect([13]),
            ],
            [
                'name' => 'Bàn ghế',
                'type' => 0,
                'business_field_id' => collect([14]),
            ],
            [
                'name' => 'Bột giặt',
                'type' => 0,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Ô tô',
                'type' => 0,
                'business_field_id' => collect([15]),
            ],
            [
                'name' => 'Tạo kiểu tóc',
                'type' => 0,
                'business_field_id' => collect([16]),
            ],
            [
                'name' => 'Xe máy',
                'type' => 0,
                'business_field_id' => collect([15]),
            ],
            [
                'name' => 'Điều hòa',
                'type' => 0,
                'business_field_id' => collect([7]),
            ],
            [
                'name' => 'Sắt thép',
                'type' => 0,
                'business_field_id' => collect([3]),
            ],
            [
                'name' => 'Adidas',
                'type' => 1,
                'business_field_id' => collect([5]),
            ],
            [
                'name' => 'Gucci',
                'type' => 1,
                'business_field_id' => collect([5]),
            ],
            [
                'name' => 'Dior',
                'type' => 1,
                'business_field_id' => collect([5]),
            ],
            [
                'name' => 'Làm xoăn',
                'type' => 1,
                'business_field_id' => collect([16]),
            ],
            [
                'name' => 'Uốn',
                'type' => 1,
                'business_field_id' => collect([16]),
            ],
            [
                'name' => 'Omachi',
                'type' => 1,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Kokomi',
                'type' => 1,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Hảo hảo',
                'type' => 1,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Bảo hiểm kỹ thuật ô tô',
                'type' => 1,
                'business_field_id' => collect([2]),
            ],
            [
                'name' => 'Bảo hiểm trách nhiệm dân sự ô tô',
                'type' => 1,
                'business_field_id' => collect([2]),
            ],
            [
                'name' => 'Hồng đỏ',
                'type' => 1,
                'business_field_id' => collect([10]),
            ],
            [
                'name' => 'Hồng trắng',
                'type' => 1,
                'business_field_id' => collect([10]),
            ],
            [
                'name' => 'Wave',
                'type' => 1,
                'business_field_id' => collect([2]),
            ],
            [
                'name' => 'Dream',
                'type' => 1,
                'business_field_id' => collect([2]),
            ],
            [
                'name' => 'cho thuê phòng trọ',
                'type' => 1,
                'business_field_id' => collect([4]),
            ],
            [
                'name' => 'Cho thuê căn hộ',
                'type' => 1,
                'business_field_id' => collect([4]),
            ],

            [
                'name' => 'Panasoinc',
                'type' => 1,
                'business_field_id' => collect([7]),
            ],
            [
                'name' => 'Daikin',
                'type' => 1,
                'business_field_id' => collect([7]),
            ],
            [
                'name' => 'Thép Việt Nhật',
                'type' => 1,
                'business_field_id' => collect([3]),
            ],
            [
                'name' => 'Thép Việt Ý',
                'type' => 1,
                'business_field_id' => collect([3]),
            ],
            [
                'name' => 'Omo',
                'type' => 1,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Tide',
                'type' => 1,
                'business_field_id' => collect([1]),
            ],
            [
                'name' => 'Honda',
                'type' => 1,
                'business_field_id' => collect([15]),
            ],
            [
                'name' => 'Lexus',
                'type' => 1,
                'business_field_id' => collect([15]),
            ],
            [
                'name' => 'Herman Miller',
                'type' => 1,
                'business_field_id' => collect([14]),
            ],
            [
                'name' => 'Hòa Phát',
                'type' => 1,
                'business_field_id' => collect([14]),
            ],
            [
                'name' => 'Penguin Random House',
                'type' => 1,
                'business_field_id' => collect([13]),
            ],
            [
                'name' => 'Simon & Schuster',
                'type' => 1,
                'business_field_id' => collect([13]),
            ],
            [
                'name' => 'La Mer',
                'type' => 1,
                'business_field_id' => collect([9]),
            ],
            [
                'name' => 'Clinique',
                'type' => 1,
                'business_field_id' => collect([9]),
            ],
            [
                'name' => 'Hộp',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Thùng',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Cái',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Kilogram',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Chiếc',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Đôi',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Bộ',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Gói',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Cuộn',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Túi',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Chai',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Bao',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Mét',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Bịch',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Cặp',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ],
            [
                'name' => 'Lọ',
                'type' => 2,
                'business_field_id' => $businessFields->pluck('id'),
            ]

        ];

        foreach ($seeds as $seed) {
            $businessFieldIds = $seed['business_field_id'];
//            dd($businessFieldIds);
            unset($seed['business_field_id']);
            $seed = Seed::query()->create($seed);
            $seed->businessFieldSeed()->attach($businessFieldIds);
        }
    }
}
