<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            [
                'category' => 'HPL',
                'name' => 'TH 001 AA',
                'brand' => 'Taco',
                'variant' => 'White Glossy',
                'unit' => 'sheet',
                'price_estimate' => 350000,
                'is_active' => true,
            ],
            [
                'category' => 'Plywood',
                'name' => 'Triplek 18 mm polos',
                'brand' => null,
                'variant' => '18mm',
                'unit' => 'sheet',
                'price_estimate' => 250000,
                'is_active' => true,
            ],
            [
                'category' => 'Handle',
                'name' => 'Handle Stainless',
                'brand' => null,
                'variant' => '128mm',
                'unit' => 'pcs',
                'price_estimate' => 35000,
                'is_active' => true,
            ],
            [
                'category' => 'Edging',
                'name' => 'Edging PVC',
                'brand' => null,
                'variant' => 'White',
                'unit' => 'roll',
                'price_estimate' => 50000,
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            Material::updateOrCreate(
                [
                    'name' => $material['name'],
                    'brand' => $material['brand'],
                    'variant' => $material['variant'],
                ],
                $material
            );
        }
    }
}