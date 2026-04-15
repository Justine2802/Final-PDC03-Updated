<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->data() as $regionName => $provinces) {
            $region = Region::create(['name' => $regionName]);

            foreach ($provinces as $provinceName => $cities) {
                $province = Province::create([
                    'region_id' => $region->id,
                    'name' => $provinceName,
                ]);

                foreach ($cities as $cityInfo => $barangays) {
                    [$cityName, $zipCode] = explode('|', $cityInfo);

                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                        'zip_code' => $zipCode ?: null,
                    ]);

                    foreach ($barangays as $barangayName) {
                        Barangay::create([
                            'city_id' => $city->id,
                            'name' => $barangayName,
                        ]);
                    }
                }
            }
        }
    }

    private function data(): array
    {
        return [
            // =====================================================
            // NATIONAL CAPITAL REGION (NCR)
            // =====================================================
            'National Capital Region (NCR)' => [

                'Second District' => [
                    'City of Mandaluyong|1550' => [
                        'Addition Hills', 'Bagong Silang', 'Barangka Drive', 'Buayang Bato',
                        'Highway Hills', 'Hulo', 'Mauway', 'Namayan', 'New Zañiga',
                        'Old Zañiga', 'Plainview', 'Pleasant Hills', 'Poblacion',
                        'Wack-Wack Greenhills',
                    ],
                    'Quezon City|1100' => [
                        'Bagong Pag-asa', 'Bahay Toro', 'Batasan Hills', 'Commonwealth',
                        'Culiat', 'Diliman', 'Fairview', 'Holy Spirit', 'Kamuning',
                        'Katipunan', 'Loyola Heights', 'New Era', 'Novaliches Proper',
                        'Old Balara', 'Payatas', 'Pinyahan', 'Project 6', 'Sauyo',
                        'Sikatuna Village', 'South Triangle', 'Tandang Sora',
                        'Teachers Village East', 'UP Campus', 'West Triangle',
                    ],
                ],

                'Fourth District' => [
                    'City of Makati|1200' => [
                        'Bangkal', 'Bel-Air', 'Carmona', 'Cembo', 'Comembo', 'Dasmariñas',
                        'Forbes Park', 'Guadalupe Nuevo', 'Guadalupe Viejo', 'Kasilawan',
                        'La Paz', 'Magallanes', 'Olympia', 'Palanan', 'Pembo',
                        'Pio Del Pilar', 'Poblacion', 'Rizal', 'San Antonio', 'San Isidro',
                        'San Lorenzo', 'Santa Cruz', 'Tejeros', 'Urdaneta', 'Valenzuela',
                        'West Rembo',
                    ],
                    'City of Taguig|1630' => [
                        'Bagumbayan', 'Central Bicutan', 'Central Signal Village',
                        'Fort Bonifacio', 'Hagonoy', 'Katuparan', 'Ligid-Tipas',
                        'Lower Bicutan', 'Maharlika Village', 'Napindan',
                        'New Lower Bicutan', 'North Signal Village', 'Palingon',
                        'Pinagsama', 'San Martin De Porres', 'Santa Ana',
                        'South Signal Village', 'Tanyag', 'Upper Bicutan', 'Ususan',
                        'Wawa', 'Western Bicutan',
                    ],
                ],
            ],

            // =====================================================
            // REGION III — CENTRAL LUZON
            // =====================================================
            'Region III - Central Luzon' => [

                'Bulacan' => [
                    'City of Malolos|3000' => [
                        'Atlag', 'Bulihan', 'Dakila', 'Ligas', 'Longos',
                        'Mojon', 'Niugan', 'Poblacion', 'Santisima Trinidad', 'Tikay',
                    ],
                    'City of Meycauayan|3020' => [
                        'Bagbaguin', 'Bancal', 'Hulo', 'Liputan', 'Longos',
                        'Malhacan', 'Pajo', 'Pandayan', 'Poblacion', 'Saluysoy',
                    ],
                    'Balagtas|3016' => [
                        'Borol 1st', 'Borol 2nd', 'Longos', 'Panginay',
                        'Poblacion', 'Santol', 'Wawa',
                    ],
                    'Marilao|3019' => [
                        'Abangan Norte', 'Abangan Sur', 'Ibayo', 'Lambakin', 'Lias',
                        'Loma De Gato', 'Poblacion', 'Prenza', 'Santa Rosa',
                        'Saog', 'Tabing Ilog',
                    ],
                ],

                'Pampanga' => [
                    'Angeles City|2009' => [
                        'Anunas', 'Balibago', 'Cutcut', 'Cuayan', 'Hensonville',
                        'Holy Rosary', 'Lourdes North West', 'Malabanias', 'Margot',
                        'Mining', 'Pampang', 'Pulung Maragul', 'Salapungan',
                        'San Jose', 'Santo Domingo', 'Telabastagan',
                    ],
                    'City of Mabalacat|2010' => [
                        'Atlu-Bola', 'Bundagul', 'Cacutud', 'Calumpang',
                        'Camachiles', 'Dau', 'Dolores', 'Mabiga',
                        'Poblacion', 'San Francisco', 'Santa Ines', 'Tabun',
                    ],
                    'City of San Fernando|2000' => [
                        'Baliti', 'Bulaon', 'Calulut', 'Del Carmen', 'Dolores',
                        'Lara', 'Magliman', 'Maimpis', 'Poblacion',
                        'San Agustin', 'San Jose', 'Santo Niño', 'Telabastagan',
                    ],
                    'Magalang|2011' => [
                        'Camias', 'La Paz', 'Poblacion', 'San Agustin',
                        'San Ildefonso', 'San Nicolas', 'San Pablo', 'Santa Cruz',
                    ],
                    'Bacolor|2001' => [
                        'Balas', 'Cabambangan', 'Magliman', 'Poblacion',
                        'San Antonio', 'San Isidro', 'Santa Barbara', 'Sta. Ines',
                    ],
                ],
            ],
        ];
    }
}
