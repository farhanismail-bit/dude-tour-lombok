<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'name' => 'Toyota Avanza',
                'slug' => 'toyota-avanza',
                'type' => 'MPV',
                'passenger_capacity' => 6,
                'luggage_capacity' => 4,
                'price_per_day' => 450000,
                'description' => 'Kendaraan MPV yang nyaman untuk perjalanan keluarga dan rombongan kecil.',
                'facilities' => [
                    'AC',
                    'Bluetooth',
                    'USB Charger',
                    'Driver',
                ],
                'image' => null,
                'is_available' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Toyota Innova',
                'slug' => 'toyota-innova',
                'type' => 'MPV Premium',
                'passenger_capacity' => 6,
                'luggage_capacity' => 5,
                'price_per_day' => 650000,
                'description' => 'Kendaraan nyaman dengan ruang kabin lebih luas untuk perjalanan wisata.',
                'facilities' => [
                    'AC',
                    'Bluetooth',
                    'USB Charger',
                    'Driver',
                    'Premium Interior',
                ],
                'image' => null,
                'is_available' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Toyota Hiace',
                'slug' => 'toyota-hiace',
                'type' => 'Minibus',
                'passenger_capacity' => 15,
                'luggage_capacity' => 10,
                'price_per_day' => 900000,
                'description' => 'Pilihan ideal untuk rombongan besar yang ingin menjelajahi Lombok bersama.',
                'facilities' => [
                    'AC',
                    'Bluetooth',
                    'USB Charger',
                    'Driver',
                    'Large Luggage Space',
                ],
                'image' => null,
                'is_available' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Toyota Alphard',
                'slug' => 'toyota-alphard',
                'type' => 'Luxury MPV',
                'passenger_capacity' => 6,
                'luggage_capacity' => 4,
                'price_per_day' => 1500000,
                'description' => 'Kendaraan premium untuk perjalanan eksklusif dan kebutuhan VIP.',
                'facilities' => [
                    'AC',
                    'Leather Seat',
                    'Bluetooth',
                    'USB Charger',
                    'Driver',
                    'Premium Interior',
                ],
                'image' => null,
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(
                [
                    'slug' => $vehicle['slug'],
                ],
                $vehicle
            );
        }
    }
}