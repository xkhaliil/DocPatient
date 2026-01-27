<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthTip;

class HealthTipSeeder extends Seeder
{
    public function run(): void
    {
        HealthTip::insert([
            [
                'title' => 'Stay Hydrated',
                'description' => 'Drinking enough water daily supports digestion, circulation, and temperature regulation.',
                'category' => 'Nutrition',
                'source' => 'WHO',
            ],
            [
                'title' => 'Get Enough Sleep',
                'description' => 'Adults should aim for 7–9 hours of sleep to support brain and immune health.',
                'category' => 'Sleep',
                'source' => 'CDC',
            ],
            [
                'title' => 'Wash Your Hands',
                'description' => 'Regular handwashing reduces the spread of infections and viruses.',
                'category' => 'Hygiene',
                'source' => 'WHO',
            ],
            [
                'title' => 'Take Daily Walks',
                'description' => 'Walking 30 minutes a day improves heart health and reduces stress.',
                'category' => 'Fitness',
                'source' => 'Mayo Clinic',
            ],
            [
                'title' => 'Eat More Vegetables',
                'description' => 'Vegetables provide essential vitamins, minerals, and fiber.',
                'category' => 'Nutrition',
                'source' => 'Harvard Health',
            ],
            [
                'title' => 'Limit Sugar Intake',
                'description' => 'Reducing sugar helps prevent obesity, diabetes, and tooth decay.',
                'category' => 'Nutrition',
                'source' => 'CDC',
            ],
            [
                'title' => 'Manage Stress',
                'description' => 'Deep breathing and mindfulness can reduce stress and anxiety.',
                'category' => 'Mental Health',
                'source' => 'APA',
            ],
            [
                'title' => 'Maintain Good Posture',
                'description' => 'Good posture reduces back pain and improves breathing.',
                'category' => 'Wellness',
                'source' => 'Cleveland Clinic',
            ],
            [
                'title' => 'Take Screen Breaks',
                'description' => 'Rest your eyes every 20 minutes to reduce eye strain.',
                'category' => 'Vision',
                'source' => 'AAO',
            ],
            [
                'title' => 'Brush Teeth Twice Daily',
                'description' => 'Brushing twice a day helps prevent cavities and gum disease.',
                'category' => 'Dental',
                'source' => 'ADA',
            ],
            [
                'title' => 'Eat Regular Meals',
                'description' => 'Consistent meals help regulate blood sugar levels.',
                'category' => 'Nutrition',
                'source' => 'WHO',
            ],
            [
                'title' => 'Avoid Smoking',
                'description' => 'Smoking increases the risk of heart disease, cancer, and lung disease.',
                'category' => 'Prevention',
                'source' => 'CDC',
            ],
            [
                'title' => 'Stay Physically Active',
                'description' => 'Regular activity strengthens muscles and bones.',
                'category' => 'Fitness',
                'source' => 'WHO',
            ],
            [
                'title' => 'Practice Good Hygiene',
                'description' => 'Clean habits reduce illness and improve overall health.',
                'category' => 'Hygiene',
                'source' => 'WHO',
            ],
            [
                'title' => 'Limit Salt Intake',
                'description' => 'Reducing salt helps control blood pressure.',
                'category' => 'Nutrition',
                'source' => 'WHO',
            ],
            [
                'title' => 'Protect Your Hearing',
                'description' => 'Avoid loud noises to prevent hearing loss.',
                'category' => 'Hearing',
                'source' => 'NIH',
            ],
            [
                'title' => 'Stretch Regularly',
                'description' => 'Stretching improves flexibility and reduces muscle tension.',
                'category' => 'Fitness',
                'source' => 'Mayo Clinic',
            ],
            [
                'title' => 'Get Regular Checkups',
                'description' => 'Routine health checks help detect issues early.',
                'category' => 'Prevention',
                'source' => 'CDC',
            ],
            [
                'title' => 'Eat Slowly',
                'description' => 'Eating slowly improves digestion and prevents overeating.',
                'category' => 'Nutrition',
                'source' => 'Harvard Health',
            ],
            [
                'title' => 'Stay Socially Connected',
                'description' => 'Strong social connections improve mental and emotional health.',
                'category' => 'Mental Health',
                'source' => 'NIH',
            ],
        ]);
    }
}
