<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Projects focused on web applications, websites, and web-based systems',
            ],
            [
                'name' => 'Mobile Application',
                'description' => 'Projects involving iOS, Android, or cross-platform mobile applications',
            ],
            [
                'name' => 'Artificial Intelligence & Machine Learning',
                'description' => 'Projects utilizing AI, ML, deep learning, and data science techniques',
            ],
            [
                'name' => 'Internet of Things (IoT)',
                'description' => 'Projects involving connected devices, sensors, and embedded systems',
            ],
            [
                'name' => 'Game Development',
                'description' => 'Projects focused on video games, game engines, and interactive entertainment',
            ],
            [
                'name' => 'Data Science & Analytics',
                'description' => 'Projects involving data analysis, visualization, and business intelligence',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Projects related to security, encryption, and network protection',
            ],
            [
                'name' => 'Cloud Computing',
                'description' => 'Projects utilizing cloud platforms and distributed computing',
            ],
            [
                'name' => 'Blockchain & Cryptocurrency',
                'description' => 'Projects involving blockchain technology and decentralized applications',
            ],
            [
                'name' => 'Computer Vision',
                'description' => 'Projects focused on image processing, object detection, and visual recognition',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
