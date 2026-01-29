<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthTip;
use Carbon\Carbon;

class HealthTipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $healthTips = [
            [
                'title' => '5 Essential Tips for Better Sleep',
                'description' => 'Quality sleep is crucial for your overall health and well-being.',
                'content' => 'Getting quality sleep is essential for your overall health and well-being. Establish a consistent sleep schedule by going to bed and waking up at the same time every day, even on weekends. Create a relaxing bedtime routine that includes activities like reading, gentle stretching, or meditation. Avoid screens for at least an hour before bed as the blue light can interfere with your natural sleep cycle. Keep your bedroom cool, dark, and quiet, and invest in a comfortable mattress and pillows.',
                'category' => 'Sleep Health',
                'source' => 'National Sleep Foundation',
                'author' => 'Dr. Sarah Johnson',
                'published_at' => Carbon::now()->subDays(2),
                'read_more_url' => 'https://www.thensf.org/sleep-tips/'
            ],
            [
                'title' => 'The Importance of Regular Exercise for Mental Health',
                'description' => 'Physical activity is not just good for your body, but essential for your mental well-being.',
                'content' => 'Regular physical activity is crucial for maintaining both physical and mental health. Exercise releases endorphins, often called "feel-good" hormones, which can help reduce stress, anxiety, and depression. Aim for at least 30 minutes of moderate exercise most days of the week. This can include walking, swimming, cycling, or any activity that gets your heart rate up. Even small amounts of exercise can make a big difference in your mood and energy levels.',
                'category' => 'Mental Health',
                'source' => 'American Psychological Association',
                'author' => 'Dr. Michael Chen',
                'published_at' => Carbon::now()->subDays(5),
                'read_more_url' => 'https://www.apa.org/topics/exercise-fitness'
            ],
            [
                'title' => 'Building Healthy Eating Habits for Life',
                'description' => 'A balanced diet is the foundation of good health and longevity.',
                'content' => 'A balanced diet is key to maintaining optimal health throughout your life. Focus on including plenty of fruits and vegetables in your meals - aim for at least 5 servings per day. Choose whole grains over refined grains, and include lean proteins such as fish, poultry, beans, and nuts. Limit processed foods, added sugars, and saturated fats. Stay hydrated by drinking plenty of water throughout the day. Remember that healthy eating is about consistency, not perfection.',
                'category' => 'Nutrition',
                'source' => 'Academy of Nutrition and Dietetics',
                'author' => 'Dr. Lisa Rodriguez',
                'published_at' => Carbon::now()->subDays(7),
                'read_more_url' => 'https://www.eatright.org/food/nutrition'
            ],
            [
                'title' => 'Managing Stress in the Modern World',
                'description' => 'Effective stress management techniques can significantly improve your quality of life.',
                'content' => 'Chronic stress can have serious impacts on both physical and mental health. Learning to manage stress effectively is crucial for overall well-being. Practice deep breathing exercises, which can activate your body\'s relaxation response. Try mindfulness meditation for just 10 minutes a day. Maintain social connections with friends and family. Set realistic goals and learn to say no when necessary. Consider activities like yoga, tai chi, or progressive muscle relaxation. If stress becomes overwhelming, don\'t hesitate to seek professional help.',
                'category' => 'Stress Management',
                'source' => 'Mayo Clinic',
                'author' => 'Dr. Jennifer Thompson',
                'published_at' => Carbon::now()->subDays(10),
                'read_more_url' => 'https://www.mayoclinic.org/healthy-lifestyle/stress-management'
            ],
            [
                'title' => 'The Power of Hydration: Why Water Matters',
                'description' => 'Staying properly hydrated is one of the simplest yet most important health habits.',
                'content' => 'Water is essential for every cell, tissue, and organ in your body. Proper hydration helps regulate body temperature, lubricate joints, protect sensitive tissues, and eliminate waste. The general recommendation is to drink 8 glasses of water per day, but individual needs vary based on activity level, climate, and overall health. Signs of dehydration include thirst, dry mouth, fatigue, and dark-colored urine. Keep a water bottle with you throughout the day and sip regularly, even before you feel thirsty.',
                'category' => 'General Health',
                'source' => 'CDC',
                'author' => 'Dr. Robert Martinez',
                'published_at' => Carbon::now()->subDays(12),
                'read_more_url' => 'https://www.cdc.gov/nutrition/water-intake'
            ],
            [
                'title' => 'Digital Detox: Protecting Your Mental Health',
                'description' => 'Reducing screen time can significantly improve your mental well-being and sleep quality.',
                'content' => 'Excessive screen time can negatively impact your mental health, sleep quality, and relationships. Consider implementing a digital detox routine. Set specific times for checking emails and social media rather than constantly throughout the day. Create tech-free zones in your home, especially in the bedroom. Take regular breaks from screens using the 20-20-20 rule: every 20 minutes, look at something 20 feet away for 20 seconds. Engage in offline activities like reading, walking, or spending time with loved ones.',
                'category' => 'Mental Health',
                'source' => 'American Academy of Pediatrics',
                'author' => 'Dr. Amanda White',
                'published_at' => Carbon::now()->subDays(15),
                'read_more_url' => 'https://www.aap.org/en/practice-management/digital-detox/'
            ],
            [
                'title' => 'The Benefits of Mindful Breathing',
                'description' => 'Simple breathing techniques can transform your mental state in just minutes.',
                'content' => 'Mindful breathing is one of the most accessible and effective relaxation techniques available. Try the 4-7-8 breathing technique: inhale through your nose for 4 counts, hold your breath for 7 counts, then exhale through your mouth for 8 counts. This can help reduce anxiety, lower blood pressure, and improve sleep quality. Practice for just 5-10 minutes daily, and you\'ll notice improvements in your overall stress levels and emotional regulation.',
                'category' => 'Mindfulness',
                'source' => 'Harvard Health Publishing',
                'author' => 'Dr. David Lee',
                'published_at' => Carbon::now()->subDays(18),
                'read_more_url' => 'https://www.health.harvard.edu/mind-and-mood/relaxation-techniques-breath-control-helps-quell-errant-stress-response'
            ],
            [
                'title' => 'Building Strong Social Connections',
                'description' => 'Strong social relationships are as important to health as diet and exercise.',
                'content' => 'Social connections are vital for mental and physical health. People with strong social relationships have lower rates of anxiety and depression, higher self-esteem, and even live longer. Make time for regular social activities, whether it\'s meeting friends for coffee, joining a club, or volunteering in your community. Nurture existing relationships by staying in touch regularly. Don\'t underestimate the power of small interactions - even brief conversations with neighbors or colleagues can boost your mood.',
                'category' => 'Mental Health',
                'source' => 'American Psychological Association',
                'author' => 'Dr. Patricia Brown',
                'published_at' => Carbon::now()->subDays(20),
                'read_more_url' => 'https://www.apa.org/topics/social-support'
            ]
        ];

        foreach ($healthTips as $tip) {
            HealthTip::create($tip);
        }
    }
}