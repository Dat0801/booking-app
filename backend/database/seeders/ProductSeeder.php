<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::whereIn('slug', [
            'massage',
            'facial',
            'haircut',
            'consultation',
        ])->get()->keyBy('slug');

        $products = [
            [
                'name' => 'Relaxing Full Body Massage 60min',
                'slug' => 'relaxing-full-body-massage-60min',
                'type' => 'service',
                'category_slug' => 'massage',
                'description' => '60-minute full body relaxing massage.',
                'price' => 49.90,
                'duration_minutes' => 60,
                'stock_quantity' => null,
                'is_active' => true,
                'image_url' => null,
            ],
            [
                'name' => 'Deep Tissue Massage 90min',
                'slug' => 'deep-tissue-massage-90min',
                'type' => 'service',
                'category_slug' => 'massage',
                'description' => '90-minute deep tissue massage for muscle relief.',
                'price' => 79.90,
                'duration_minutes' => 90,
                'stock_quantity' => null,
                'is_active' => true,
                'image_url' => null,
            ],
            [
                'name' => 'Classic Facial Treatment',
                'slug' => 'classic-facial-treatment',
                'type' => 'service',
                'category_slug' => 'facial',
                'description' => 'Classic facial treatment for all skin types.',
                'price' => 39.90,
                'duration_minutes' => 45,
                'stock_quantity' => null,
                'is_active' => true,
                'image_url' => null,
            ],
            [
                'name' => 'Standard Haircut',
                'slug' => 'standard-haircut',
                'type' => 'service',
                'category_slug' => 'haircut',
                'description' => 'Standard haircut and basic styling.',
                'price' => 19.90,
                'duration_minutes' => 30,
                'stock_quantity' => null,
                'is_active' => true,
                'image_url' => null,
            ],
            [
                'name' => 'Online Consultation 30min',
                'slug' => 'online-consultation-30min',
                'type' => 'service',
                'category_slug' => 'consultation',
                'description' => '30-minute online consultation session.',
                'price' => 29.90,
                'duration_minutes' => 30,
                'stock_quantity' => null,
                'is_active' => true,
                'image_url' => null,
            ],
        ];

        foreach ($products as $data) {
            $categoryId = null;

            if (! empty($data['category_slug']) && isset($categories[$data['category_slug']])) {
                $categoryId = $categories[$data['category_slug']]->id;
            }

            Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'category_id' => $categoryId,
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'duration_minutes' => $data['duration_minutes'],
                    'stock_quantity' => $data['stock_quantity'],
                    'is_active' => $data['is_active'],
                    'image_url' => $data['image_url'],
                ]
            );
        }
    }
}

