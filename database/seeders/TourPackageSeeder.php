<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourPackageSeeder extends Seeder
{
    public function run(): void
    {
        $gili = Destination::where('slug', 'gili-trawangan')->first();
        $kuta = Destination::where('slug', 'kuta-mandalika')->first();
        $pinkBeach = Destination::where('slug', 'pink-beach')->first();
        $tanjungAan = Destination::where('slug', 'tanjung-aan')->first();
        $sendangGile = Destination::where(
            'slug',
            'air-terjun-sendang-gile'
        )->first();

        $packages = [
            [
                'name' => 'Lombok Highlight Tour',
                'slug' => 'lombok-highlight-tour',
                'tag' => 'PRIVATE TOUR',
                'short_description' => 'Nikmati destinasi terbaik Lombok dalam satu perjalanan yang nyaman.',
                'description' => 'Paket perjalanan untuk menikmati beberapa destinasi populer Lombok dengan perjalanan yang nyaman dan fleksibel.',
                'price' => 750000,
                'duration_days' => 1,
                'duration_nights' => 0,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'destinations' => [
                    $kuta?->id,
                    $tanjungAan?->id,
                    $gili?->id,
                ],
            ],

            [
                'name' => 'South Lombok Escape',
                'slug' => 'south-lombok-escape',
                'tag' => 'BEACH TOUR',
                'short_description' => 'Jelajahi pantai-pantai indah di Mandalika dan Lombok Selatan.',
                'description' => 'Paket wisata untuk menikmati keindahan pantai dan kawasan Mandalika di Lombok Selatan.',
                'price' => 650000,
                'duration_days' => 1,
                'duration_nights' => 0,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'destinations' => [
                    $kuta?->id,
                    $tanjungAan?->id,
                    $pinkBeach?->id,
                ],
            ],

            [
                'name' => 'North Lombok Adventure',
                'slug' => 'north-lombok-adventure',
                'tag' => 'WATERFALL',
                'short_description' => 'Rasakan petualangan menuju air terjun dan alam Lombok Utara.',
                'description' => 'Paket perjalanan alam untuk menjelajahi kawasan Lombok Utara dan menikmati keindahan air terjun.',
                'price' => 800000,
                'duration_days' => 1,
                'duration_nights' => 0,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'destinations' => [
                    $sendangGile?->id,
                ],
            ],
        ];

        foreach ($packages as $packageData) {
            $destinationIds = $packageData['destinations'];

            unset($packageData['destinations']);

            $package = TourPackage::updateOrCreate(
                [
                    'slug' => $packageData['slug'],
                ],
                $packageData
            );

            $destinationIds = array_values(
                array_filter($destinationIds)
            );

            $syncData = [];

            foreach ($destinationIds as $index => $destinationId) {
                $syncData[$destinationId] = [
                    'sort_order' => $index + 1,
                ];
            }

            $package->destinations()->sync($syncData);
        }
    }
}