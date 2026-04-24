<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EngineType;
use App\Models\CarType;
use App\Models\ItemCategory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEEDER KARYAWAN (Admin & Mekanik)
        Employee::create([
            'name' => 'Brok Admin',
            'join_date' => '2026-01-01',
            'birth_date' => '1995-05-20',
            'address' => 'Jl. Pahlawan No. 123, Depok',
            'email' => 'admin@garasibmw.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'base_salary' => 8000000,
            'status' => true,
        ]);

        // 2. SEEDER JENIS MESIN (Master Mesin BMW)
        $m54 = EngineType::create([
            'name' => 'M54',
            'cylinders' => 'Inline 6',
            'oil_cap' => 7.0,
            'fuel_type' => 'Bensin',
            'engine_cap' => 3000,
            'created_by' => 1
        ]);

        $n52 = EngineType::create([
            'name' => 'N52',
            'cylinders' => 'Inline 6',
            'oil_cap' => 6.5,
            'fuel_type' => 'Bensin',
            'engine_cap' => 2500,
            'created_by' => 1
        ]);

        $n42 = EngineType::create([
            'name' => 'N42',
            'cylinders' => 'Inline 4',
            'oil_cap' => 4.5,
            'fuel_type' => 'Bensin',
            'engine_cap' => 2000,
            'created_by' => 1
        ]);

        $m57 = EngineType::create([
            'name' => 'M57',
            'cylinders' => 'Inline 6',
            'oil_cap' => 7.5,
            'fuel_type' => 'Diesel',
            'engine_cap' => 3000,
            'created_by' => 1
        ]);

        // 3. SEEDER JENIS MOBIL (Pake fitur Multiple Engine)
        CarType::create([
            'chassis_number' => 'E46',
            'name' => 'BMW E46 325i',
            'series' => '3 Series',
            'engine_type_id' => $m54->engine_type_id,
            'engine_code' => 'M54, N42', // Rangkuman nama mesin
            'created_by' => 1
        ]);

        CarType::create([
            'chassis_number' => 'E39',
            'name' => 'BMW E39 530i',
            'series' => '5 Series',
            'engine_type_id' => $m54->engine_type_id,
            'engine_code' => 'M54, M57',
            'created_by' => 1
        ]);

        CarType::create([
            'chassis_number' => 'F10',
            'name' => 'BMW F10 520i',
            'series' => '5 Series',
            'engine_type_id' => $n52->engine_type_id,
            'engine_code' => 'N52, N20',
            'created_by' => 1
        ]);

        // 4. SEEDER KATEGORI SPAREPART
        $categories = [
            ['name' => 'Mesin', 'descriptions' => 'Komponen internal mesin dan pendukungnya'],
            ['name' => 'Kaki-kaki', 'descriptions' => 'Suspensi, shockbreaker, dan bushing'],
            ['name' => 'Pengereman', 'descriptions' => 'Kampas rem, piringan, dan sensor ABS'],
            ['name' => 'Elektrikal', 'descriptions' => 'Sensor-sensor, modul, dan kabel bodi'],
            ['name' => 'Oli & Cairan', 'descriptions' => 'Pelumas mesin, transmisi, dan radiator coolant'],
        ];

        foreach ($categories as $cat) {
            ItemCategory::create([
                'name' => $cat['name'],
                'descriptions' => $cat['descriptions'],
                'created_by' => 1
            ]);
        }

        // 5. SEEDER SUPPLIER
        $suppliers = [
            ['name' => 'PT. Sumber Makmur', 'description' => 'Supplier resmi sparepart BMW original'],
            ['name' => 'CV. Jaya Abadi Parts', 'description' => 'Spesialis part aftermarket Jerman'],
            ['name' => 'Garasi BMW Parts', 'description' => 'Importir part copotan luar negeri'],
            ['name' => 'Mitra Autoparts', 'description' => 'Supplier ban dan pelumas'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create([
                'name' => $sup['name'],
                'description' => $sup['description']
            ]);
        }
    }
}
