<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Web Technologies
            'Laravel',
            'React',
            'Vue.js',
            'Angular',
            'Node.js',
            'PHP',
            'JavaScript',
            'TypeScript',
            'HTML/CSS',
            'TailwindCSS',
            'Bootstrap',
            
            // Mobile Technologies
            'Flutter',
            'React Native',
            'Swift',
            'Kotlin',
            'Android',
            'iOS',
            
            // Programming Languages
            'Python',
            'Java',
            'C++',
            'C#',
            'Go',
            'Rust',
            
            // AI/ML
            'Machine Learning',
            'Deep Learning',
            'TensorFlow',
            'PyTorch',
            'Natural Language Processing',
            'Computer Vision',
            
            // Databases
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'SQLite',
            'Redis',
            
            // IoT & Hardware
            'Arduino',
            'Raspberry Pi',
            'ESP32',
            'Sensors',
            'Embedded Systems',
            
            // Cloud & DevOps
            'AWS',
            'Azure',
            'Google Cloud',
            'Docker',
            'Kubernetes',
            'CI/CD',
            
            // Other
            'Blockchain',
            'REST API',
            'GraphQL',
            'Microservices',
            'Agile',
            'Scrum',
        ];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
    }
}
