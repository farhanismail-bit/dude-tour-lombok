<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Gili Trawangan',
                'slug' => 'gili-trawangan',
                'short_description' => 'Pulau tropis dengan pantai indah dan suasana wisata yang menyenangkan.',
                'description' => 'Gili Trawangan merupakan salah satu destinasi populer di Lombok yang menawarkan pantai berpasir putih, air laut yang jernih, serta berbagai aktivitas wisata.',
                'location' => 'Lombok Utara',
                'category' => 'Island',
                'image' => null,
                'is_active' => true,
            ],

            [
                'name' => 'Kuta Mandalika',
                'slug' => 'kuta-mandalika',
                'short_description' => 'Destinasi wisata pantai dengan pemandangan alam yang menakjubkan.',
                'description' => 'Kuta Mandalika menawarkan perpaduan pantai, bukit, dan panorama alam Lombok Selatan yang cocok untuk wisata keluarga maupun perjalanan bersama teman.',
                'location' => 'Lombok Tengah',
                'category' => 'Beach',
                'image' => null,
                'is_active' => true,
            ],

            [
                'name' => 'Pink Beach',
                'slug' => 'pink-beach',
                'short_description' => 'Pantai unik dengan pasir berwarna merah muda dan air laut yang jernih.',
                'description' => 'Pink Beach merupakan salah satu destinasi unik di Lombok yang terkenal dengan warna pasirnya dan panorama laut yang indah.',
                'location' => 'Lombok Timur',
                'category' => 'Beach',
                'image' => null,
                'is_active' => true,
            ],

            [
                'name' => 'Tanjung Aan',
                'slug' => 'tanjung-aan',
                'short_description' => 'Pantai dengan pasir putih dan panorama laut yang luas.',
                'description' => 'Tanjung Aan merupakan destinasi populer di kawasan Mandalika yang memiliki garis pantai yang indah serta perbukitan dengan pemandangan laut.',
                'location' => 'Lombok Tengah',
                'category' => 'Beach',
                'image' => null,
                'is_active' => true,
            ],

            [
                'name' => 'Air Terjun Sendang Gile',
                'slug' => 'air-terjun-sendang-gile',
                'short_description' => 'Air terjun alami di kawasan Lombok Utara dengan suasana yang sejuk.',
                'description' => 'Air Terjun Sendang Gile merupakan salah satu destinasi wisata alam Lombok Utara yang berada di kawasan kaki Gunung Rinjani.',
                'location' => 'Lombok Utara',
                'category' => 'Waterfall',
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::updateOrCreate(
                [
                    'slug' => $destination['slug'],
                ],
                $destination
            );
        }
    }
}