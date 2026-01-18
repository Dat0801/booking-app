<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Spa & Wellness',
                'slug' => 'spa-wellness',
                'description' => 'Spa and wellness services.',
                'is_active' => true,
            ],
            [
                'name' => 'Beauty & Hair',
                'slug' => 'beauty-hair',
                'description' => 'Beauty and hair services.',
                'is_active' => true,
            ],
            [
                'name' => 'Consultation',
                'slug' => 'consultation',
                'description' => 'Consultation and coaching sessions.',
                'is_active' => true,
            ],
        ];

        $categoryMap = [];

        foreach ($categories as $data) {
            $category = Category::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_active' => $data['is_active'],
                    'parent_id' => null,
                ]
            );

            $categoryMap[$data['slug']] = $category->id;
        }

        $subCategories = [
            [
                'name' => 'Massage',
                'slug' => 'massage',
                'parent_slug' => 'spa-wellness',
                'description' => 'Massage services.',
                'is_active' => true,
            ],
            [
                'name' => 'Facial',
                'slug' => 'facial',
                'parent_slug' => 'spa-wellness',
                'description' => 'Facial and skincare services.',
                'is_active' => true,
            ],
            [
                'name' => 'Haircut',
                'slug' => 'haircut',
                'parent_slug' => 'beauty-hair',
                'description' => 'Haircut and styling.',
                'is_active' => true,
            ],
        ];

        foreach ($subCategories as $data) {
            $parentId = $categoryMap[$data['parent_slug']] ?? null;

            Category::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_active' => $data['is_active'],
                    'parent_id' => $parentId,
                ]
            );
        }
    }
}

