<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Provinces;
use App\Models\Regency;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data provinsi
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();

        foreach ($provinces as $prov) {
            $province = Provinces::create([
                'id' => $prov['id'],
                'name' => $prov['name'],
            ]);

            // Ambil data kabupaten/kota berdasarkan provinsi
            $regencies = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$prov['id']}.json")->json();

            foreach ($regencies as $reg) {
                Regency::create([
                    'id' => $reg['id'],
                    'province_id' => $province->id,
                    'name' => $reg['name'],
                ]);
            }
        }
    }
}
