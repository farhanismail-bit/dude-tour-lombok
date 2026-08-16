<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Private Tour',
                'slug' => 'private-tour',
                'short_description' => 'Perjalanan private yang fleksibel dan disesuaikan dengan kebutuhan Anda.',
                'description' => 'Nikmati perjalanan private bersama keluarga, pasangan, teman, atau rombongan dengan itinerary yang fleksibel.',
                'icon' => 'compass',
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Transport',
                'slug' => 'transport',
                'short_description' => 'Layanan transportasi nyaman untuk menjelajahi berbagai destinasi Lombok.',
                'description' => 'Layanan transportasi dengan pilihan kendaraan yang nyaman untuk perjalanan wisata, keluarga, bisnis, maupun kebutuhan lainnya.',
                'icon' => 'car',
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Airport Transfer',
                'slug' => 'airport-transfer',
                'short_description' => 'Antar jemput bandara yang aman dan nyaman.',
                'description' => 'Layanan antar jemput dari dan menuju Bandara Internasional Lombok dengan driver yang berpengalaman.',
                'icon' => 'plane',
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Custom Trip',
                'slug' => 'custom-trip',
                'short_description' => 'Rancang perjalanan Lombok sesuai keinginan Anda.',
                'description' => 'Buat itinerary perjalanan sendiri sesuai waktu, destinasi, jumlah peserta, dan kebutuhan perjalanan Anda.',
                'icon' => 'map',
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                [
                    'slug' => $service['slug'],
                ],
                $service
            );
        }
    }
}