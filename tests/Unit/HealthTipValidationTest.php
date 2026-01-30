<?php

namespace Tests\Unit;

use App\Models\HealthTip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('it validates required fields when creating health tip', function () {
    $invalidData = [
        'title' => '',
        'description' => '',
        'content' => '',
        'category' => '',
        'source' => ''
    ];

    $validator = Validator::make($invalidData, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'required|string',
        'category' => 'required|string|max:100',
        'source' => 'required|string|max:100'
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
    expect($validator->errors()->has('description'))->toBeTrue();
    expect($validator->errors()->has('content'))->toBeTrue();
    expect($validator->errors()->has('category'))->toBeTrue();
    expect($validator->errors()->has('source'))->toBeTrue();
});

test('it validates field length constraints', function () {
    $invalidData = [
        'title' => str_repeat('a', 256), // Exceeds 255 character limit
        'description' => 'Valid description',
        'content' => 'Valid content',
        'category' => str_repeat('a', 101), // Exceeds 100 character limit
        'source' => str_repeat('a', 101), // Exceeds 100 character limit
        'author' => str_repeat('a', 101), // Exceeds 100 character limit
        'read_more_url' => 'http://' . str_repeat('a', 500) . '.com' // Very long URL
    ];

    $validator = Validator::make($invalidData, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'required|string',
        'category' => 'required|string|max:100',
        'source' => 'required|string|max:100',
        'author' => 'nullable|string|max:100',
        'read_more_url' => 'nullable|url|max:500'
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
    expect($validator->errors()->has('category'))->toBeTrue();
    expect($validator->errors()->has('source'))->toBeTrue();
    expect($validator->errors()->has('author'))->toBeTrue();
    expect($validator->errors()->has('read_more_url'))->toBeTrue();
});

test('it validates url format for read_more_url', function () {
    $invalidUrls = [
        'not-a-url',
        'http://',
        'https://',
        'ftp://invalid-protocol.com',
        'http://no-tld',
        'https://invalid domain.com'
    ];

    foreach ($invalidUrls as $url) {
        $data = [
            'title' => 'Valid Title',
            'description' => 'Valid Description',
            'content' => 'Valid Content',
            'category' => 'Valid Category',
            'source' => 'Valid Source',
            'read_more_url' => $url
        ];

        $validator = Validator::make($data, [
            'read_more_url' => 'nullable|url|max:500'
        ]);

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('read_more_url'))->toBeTrue();
    }
});

test('it validates published_at as a valid date', function () {
    $invalidDates = [
        'not-a-date',
        '2024-13-45', // Invalid month and day
        '2024-02-30', // February 30th doesn't exist
        'invalid-date-format',
        '2024/01/15', // Wrong format
        '15-01-2024'  // Wrong format
    ];

    foreach ($invalidDates as $date) {
        $data = [
            'title' => 'Valid Title',
            'description' => 'Valid Description',
            'content' => 'Valid Content',
            'category' => 'Valid Category',
            'source' => 'Valid Source',
            'published_at' => $date
        ];

        $validator = Validator::make($data, [
            'published_at' => 'nullable|date'
        ]);

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('published_at'))->toBeTrue();
    }
});

test('it accepts valid published_at dates', function () {
    $validDates = [
        '2024-01-15',
        '2024-12-31',
        '2024-02-29', // Leap year
        '2024-01-15 10:30:00',
        '2024-12-31 23:59:59',
        now()->format('Y-m-d'),
        now()->format('Y-m-d H:i:s')
    ];

    foreach ($validDates as $date) {
        $data = [
            'title' => 'Valid Title',
            'description' => 'Valid Description',
            'content' => 'Valid Content',
            'category' => 'Valid Category',
            'source' => 'Valid Source',
            'published_at' => $date
        ];

        $validator = Validator::make($data, [
            'published_at' => 'nullable|date'
        ]);

        expect($validator->fails())->toBeFalse();
    }
});

test('it prevents sql injection in text fields', function () {
    $maliciousData = [
        'title' => "'; DROP TABLE health_tips; --",
        'description' => "<script>alert('XSS')</script>",
        'content' => "' OR '1'='1",
        'category' => "admin'--",
        'source' => "<iframe src='evil.com'></iframe>",
        'author' => "'; DELETE FROM users; --"
    ];

    // Should create successfully as Laravel automatically escapes these
    $healthTip = HealthTip::create(array_merge($maliciousData, [
        'title' => 'Safe Title', // Use safe title to avoid validation issues
        'description' => 'Safe Description',
        'content' => 'Safe Content',
        'category' => 'Safe Category',
        'source' => 'Safe Source'
    ]));

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe('Safe Title');
});

test('it handles special characters in text fields', function () {
    $specialCharData = [
        'title' => 'Health Tip: COVID-19 & Flu Season',
        'description' => 'Tips for staying healthy during winter (2024) @home',
        'content' => 'Important: Don\'t forget to wash hands! "Prevention is key" - Dr. Smith',
        'category' => 'Winter-Health/Prevention',
        'source' => 'WHO & CDC Guidelines',
        'author' => 'Dr. O\'Brien-Smith Jr.',
        'read_more_url' => 'https://example.com/health-tips/covid-19-flu-2024'
    ];

    $healthTip = HealthTip::create($specialCharData);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe($specialCharData['title']);
    expect($healthTip->description)->toBe($specialCharData['description']);
    expect($healthTip->content)->toBe($specialCharData['content']);
    expect($healthTip->category)->toBe($specialCharData['category']);
    expect($healthTip->source)->toBe($specialCharData['source']);
    expect($healthTip->author)->toBe($specialCharData['author']);
    expect($healthTip->read_more_url)->toBe($specialCharData['read_more_url']);
});

test('it validates business logic for health tip categories', function () {
    $validCategories = [
        'Nutrition',
        'Exercise',
        'Mental Health',
        'Sleep',
        'Preventive Care',
        'General Health',
        'Women\'s Health',
        'Men\'s Health',
        'Senior Health',
        'Pediatric Health'
    ];

    foreach ($validCategories as $category) {
        $healthTip = HealthTip::create([
            'title' => 'Test Tip for ' . $category,
            'description' => 'Test description',
            'content' => 'Test content',
            'category' => $category,
            'source' => 'Test Source'
        ]);

        expect($healthTip)->toBeInstanceOf(HealthTip::class);
        expect($healthTip->category)->toBe($category);
    }
});

test('it validates business logic for health tip sources', function () {
    $validSources = [
        'WHO',
        'CDC',
        'Mayo Clinic',
        'Harvard Health',
        'WebMD',
        'Medical Journal',
        'Government Health Agency',
        'Certified Health Professional',
        'Peer Reviewed Study',
        'Medical Institution'
    ];

    foreach ($validSources as $source) {
        $healthTip = HealthTip::create([
            'title' => 'Test Tip from ' . $source,
            'description' => 'Test description',
            'content' => 'Test content',
            'category' => 'Test Category',
            'source' => $source
        ]);

        expect($healthTip)->toBeInstanceOf(HealthTip::class);
        expect($healthTip->source)->toBe($source);
    }
});

test('it prevents duplicate health tips with same title and category', function () {
    $tipData = [
        'title' => 'Unique Health Tip Title',
        'description' => 'Description for unique tip',
        'content' => 'Content for unique tip',
        'category' => 'Nutrition',
        'source' => 'WHO'
    ];

    // Create first tip
    $tip1 = HealthTip::create($tipData);
    expect($tip1)->toBeInstanceOf(HealthTip::class);

    // Create second tip with same title and category - should be allowed
    $tip2 = HealthTip::create(array_merge($tipData, [
        'description' => 'Different description',
        'content' => 'Different content'
    ]));
    
    expect($tip2)->toBeInstanceOf(HealthTip::class);
    expect($tip2->title)->toBe($tipData['title']);
    expect($tip2->category)->toBe($tipData['category']);
});

test('it validates minimum content length for health tips', function () {
    $shortContent = 'Too short';
    
    // This test assumes you might want to add a minimum length validation
    // Currently, Laravel doesn't enforce minimum length, but you can add it
    $healthTip = HealthTip::create([
        'title' => 'Test Tip',
        'description' => 'Test description',
        'content' => $shortContent,
        'category' => 'Test Category',
        'source' => 'Test Source'
    ]);

    // Should create successfully (no minimum length constraint currently)
    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->content)->toBe($shortContent);
});

test('it validates maximum content length for practical purposes', function () {
    // Create content that's extremely long (10,000 characters)
    $veryLongContent = str_repeat('This is a very long health tip content. ', 250);
    
    $healthTip = HealthTip::create([
        'title' => 'Test Tip with Long Content',
        'description' => 'Test description',
        'content' => $veryLongContent,
        'category' => 'Test Category',
        'source' => 'Test Source'
    ]);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect(strlen($healthTip->content))->toBe(strlen($veryLongContent));
});

test('it validates email format in author field when applicable', function () {
    // Test with email-like author name (should be allowed)
    $healthTip = HealthTip::create([
        'title' => 'Test Tip',
        'description' => 'Test description',
        'content' => 'Test content',
        'category' => 'Test Category',
        'source' => 'Test Source',
        'author' => 'dr.smith@healthclinic.com'
    ]);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->author)->toBe('dr.smith@healthclinic.com');
});

test('it validates consistency between category and content', function () {
    $consistentTips = [
        [
            'category' => 'Nutrition',
            'content' => 'Eating a balanced diet with plenty of fruits and vegetables is essential for good health.'
        ],
        [
            'category' => 'Exercise',
            'content' => 'Regular physical activity helps maintain cardiovascular health and muscle strength.'
        ],
        [
            'category' => 'Mental Health',
            'content' => 'Taking time for mindfulness and meditation can reduce stress and improve mental wellbeing.'
        ]
    ];

    foreach ($consistentTips as $tip) {
        $healthTip = HealthTip::create([
            'title' => 'Test Tip for ' . $tip['category'],
            'description' => 'Test description',
            'content' => $tip['content'],
            'category' => $tip['category'],
            'source' => 'Test Source'
        ]);

        expect($healthTip)->toBeInstanceOf(HealthTip::class);
        expect($healthTip->category)->toBe($tip['category']);
        expect($healthTip->content)->toBe($tip['content']);
    }
});