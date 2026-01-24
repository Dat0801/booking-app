<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $properties = [
            [
                'name' => 'Azure Oceanfront Villa',
                'slug' => 'azure-oceanfront-villa',
                'type' => 'property',
                'category_id' => null,
                'description' => 'Architectural masterpiece perched above the Pacific. This villa offers floor-to-ceiling glass walls, a private infinity edge pool, and direct beach access. Perfect for luxury vacations.',
                'price' => 1250.00,
                'duration_minutes' => null,
                'stock_quantity' => 1,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1613395877344-13d4a8e0d049?w=800',
                'location' => 'Malibu, California',
                'rating' => 4.9,
                'review_count' => 124,
                'gallery' => [
                    'https://images.unsplash.com/photo-1613395877344-13d4a8e0d049?w=800',
                    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800',
                ],
                'amenities' => [
                    ['id' => 1, 'name' => 'Fast WiFi', 'icon' => 'wifi-outline'],
                    ['id' => 2, 'name' => 'Infinity Pool', 'icon' => 'water-outline'],
                    ['id' => 3, 'name' => 'Central AC', 'icon' => 'snow-outline'],
                    ['id' => 4, 'name' => 'Full Kitchen', 'icon' => 'restaurant-outline'],
                    ['id' => 5, 'name' => 'Free Parking', 'icon' => 'local-parking-outline'],
                    ['id' => 6, 'name' => '65" HDTV', 'icon' => 'tv-outline'],
                    ['id' => 7, 'name' => 'Hot Tub', 'icon' => 'water-outline'],
                    ['id' => 8, 'name' => 'Beach Access', 'icon' => 'checkmark-circle-outline'],
                ],
                'reviews' => [
                    [
                        'id' => 1,
                        'user_name' => 'Sarah Jenkins',
                        'user_avatar' => 'https://i.pravatar.cc/150?img=1',
                        'rating' => 5,
                        'comment' => 'Absolutely stunning views! The pictures don\'t even do it justice. The check-in process was seamless.',
                        'date' => '2023-10-15'
                    ],
                    [
                        'id' => 2,
                        'user_name' => 'Michael Chen',
                        'user_avatar' => 'https://i.pravatar.cc/150?img=2',
                        'rating' => 4,
                        'comment' => 'Best villa we stayed in. Everything was perfectly maintained and the staff was very helpful.',
                        'date' => '2023-10-10'
                    ],
                ],
            ],
            [
                'name' => 'Luxury Plaza Hotel',
                'slug' => 'luxury-plaza-hotel',
                'type' => 'property',
                'category_id' => null,
                'description' => 'Experience unparalleled luxury in the heart of Paris. Five-star amenities, Michelin-star dining, and world-class service.',
                'price' => 350.00,
                'duration_minutes' => null,
                'stock_quantity' => 10,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800',
                'location' => 'Paris, France',
                'rating' => 4.8,
                'review_count' => 256,
                'gallery' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                ],
                'amenities' => [
                    ['id' => 1, 'name' => 'Fast WiFi', 'icon' => 'wifi-outline'],
                    ['id' => 2, 'name' => 'Swimming Pool', 'icon' => 'water-outline'],
                    ['id' => 3, 'name' => 'Central AC', 'icon' => 'snow-outline'],
                    ['id' => 4, 'name' => 'Restaurant', 'icon' => 'restaurant-outline'],
                    ['id' => 5, 'name' => 'Valet Parking', 'icon' => 'local-parking-outline'],
                    ['id' => 6, 'name' => '4K TV', 'icon' => 'tv-outline'],
                ],
                'reviews' => [
                    [
                        'id' => 3,
                        'user_name' => 'Emma Thompson',
                        'user_avatar' => 'https://i.pravatar.cc/150?img=3',
                        'rating' => 5,
                        'comment' => 'Incredible luxury hotel with amazing service. The breakfast was world-class!',
                        'date' => '2023-11-05'
                    ],
                ],
            ],
            [
                'name' => 'Charming Marais Studio',
                'slug' => 'charming-marais-studio',
                'type' => 'property',
                'category_id' => null,
                'description' => 'Cozy studio apartment in the historic Marais district. Perfect for couples seeking an authentic Parisian experience.',
                'price' => 180.00,
                'duration_minutes' => null,
                'stock_quantity' => 1,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'location' => 'Paris, Marais District',
                'rating' => 4.7,
                'review_count' => 89,
                'gallery' => [
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                ],
                'amenities' => [
                    ['id' => 1, 'name' => 'Fast WiFi', 'icon' => 'wifi-outline'],
                    ['id' => 2, 'name' => 'Full Kitchen', 'icon' => 'restaurant-outline'],
                    ['id' => 3, 'name' => 'Heating', 'icon' => 'snow-outline'],
                ],
                'reviews' => [],
            ],
            [
                'name' => 'Riverside Boutique Stay',
                'slug' => 'riverside-boutique-stay',
                'type' => 'property',
                'category_id' => null,
                'description' => 'Elegant boutique hotel with stunning Seine views. Intimate atmosphere with personalized service.',
                'price' => 290.00,
                'duration_minutes' => null,
                'stock_quantity' => 5,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
                'location' => 'Paris, Seine District',
                'rating' => 4.6,
                'review_count' => 145,
                'gallery' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
                ],
                'amenities' => [
                    ['id' => 1, 'name' => 'Fast WiFi', 'icon' => 'wifi-outline'],
                    ['id' => 2, 'name' => 'River View', 'icon' => 'water-outline'],
                    ['id' => 3, 'name' => 'AC', 'icon' => 'snow-outline'],
                ],
                'reviews' => [],
            ],
            [
                'name' => 'Cozy Montmartre Loft',
                'slug' => 'cozy-montmartre-loft',
                'type' => 'property',
                'category_id' => null,
                'description' => 'Spacious loft in artistic Montmartre. High ceilings, modern amenities, and close to Sacré-Cœur.',
                'price' => 220.00,
                'duration_minutes' => null,
                'stock_quantity' => 1,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                'location' => 'Paris, Montmartre',
                'rating' => 4.5,
                'review_count' => 98,
                'gallery' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                ],
                'amenities' => [
                    ['id' => 1, 'name' => 'WiFi', 'icon' => 'wifi-outline'],
                    ['id' => 2, 'name' => 'Kitchen', 'icon' => 'restaurant-outline'],
                ],
                'reviews' => [],
            ],
        ];

        foreach ($properties as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
