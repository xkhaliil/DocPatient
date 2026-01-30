<?php

namespace Tests\Unit;

use App\Models\HealthTip;
use App\Services\HealthTipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = new HealthTipService();
    Cache::flush(); // Clear cache before each test
});

test('it can get all health tips without filters', function () {
    // Create test data
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'Description 1',
        'content' => 'Content 1',
        'category' => 'Nutrition',
        'source' => 'WHO'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'Description 2',
        'content' => 'Content 2',
        'category' => 'Exercise',
        'source' => 'CDC'
    ]);

    $result = $this->service->getHealthTips();

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(2);
    expect($result['message'])->toBe('Health tips retrieved successfully');
    expect($result['count'])->toBe(2);
});

test('it can filter health tips by category', function () {
    HealthTip::create([
        'title' => 'Nutrition Tip',
        'description' => 'Nutrition Description',
        'content' => 'Nutrition Content',
        'category' => 'Nutrition',
        'source' => 'WHO'
    ]);

    HealthTip::create([
        'title' => 'Exercise Tip',
        'description' => 'Exercise Description',
        'content' => 'Exercise Content',
        'category' => 'Exercise',
        'source' => 'CDC'
    ]);

    $result = $this->service->getHealthTips(['category' => 'Nutrition']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('Nutrition Tip');
});

test('it can filter health tips by source', function () {
    HealthTip::create([
        'title' => 'WHO Tip',
        'description' => 'WHO Description',
        'content' => 'WHO Content',
        'category' => 'Health',
        'source' => 'WHO'
    ]);

    HealthTip::create([
        'title' => 'CDC Tip',
        'description' => 'CDC Description',
        'content' => 'CDC Content',
        'category' => 'Health',
        'source' => 'CDC'
    ]);

    $result = $this->service->getHealthTips(['source' => 'WHO']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('WHO Tip');
});

test('it can search health tips by title', function () {
    HealthTip::create([
        'title' => 'Sleep Better Tonight',
        'description' => 'Sleep Description',
        'content' => 'Sleep Content',
        'category' => 'Sleep',
        'source' => 'Health'
    ]);

    HealthTip::create([
        'title' => 'Exercise Daily',
        'description' => 'Exercise Description',
        'content' => 'Exercise Content',
        'category' => 'Exercise',
        'source' => 'Health'
    ]);

    $result = $this->service->getHealthTips(['search' => 'Sleep']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('Sleep Better Tonight');
});

test('it can search health tips by description', function () {
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'This tip is about nutrition and healthy eating',
        'content' => 'Content 1',
        'category' => 'Nutrition',
        'source' => 'Health'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'This tip is about exercise and fitness',
        'content' => 'Content 2',
        'category' => 'Exercise',
        'source' => 'Health'
    ]);

    $result = $this->service->getHealthTips(['search' => 'nutrition']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('Tip 1');
});

test('it can search health tips by content', function () {
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'Description 1',
        'content' => 'This content talks about vitamins and supplements',
        'category' => 'Nutrition',
        'source' => 'Health'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'Description 2',
        'content' => 'This content talks about cardio exercises',
        'category' => 'Exercise',
        'source' => 'Health'
    ]);

    $result = $this->service->getHealthTips(['search' => 'vitamins']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('Tip 1');
});

test('it can limit the number of results', function () {
    // Create 5 health tips
    for ($i = 1; $i <= 5; $i++) {
        HealthTip::create([
            'title' => "Tip $i",
            'description' => "Description $i",
            'content' => "Content $i",
            'category' => 'General',
            'source' => 'Source'
        ]);
    }

    $result = $this->service->getHealthTips(['limit' => 3]);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(3);
    expect($result['count'])->toBe(3);
});

test('it respects maximum limit of 100', function () {
    // Create 150 health tips
    for ($i = 1; $i <= 150; $i++) {
        HealthTip::create([
            'title' => "Tip $i",
            'description' => "Description $i",
            'content' => "Content $i",
            'category' => 'General',
            'source' => 'Source'
        ]);
    }

    $result = $this->service->getHealthTips(['limit' => 200]);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(100); // Should be limited to 100
    expect($result['count'])->toBe(100);
});

test('it can order results by title ascending', function () {
    HealthTip::create([
        'title' => 'Zebra Tip',
        'description' => 'Zebra Description',
        'content' => 'Zebra Content',
        'category' => 'Animals',
        'source' => 'Zoo'
    ]);

    HealthTip::create([
        'title' => 'Apple Tip',
        'description' => 'Apple Description',
        'content' => 'Apple Content',
        'category' => 'Fruits',
        'source' => 'Farm'
    ]);

    $result = $this->service->getHealthTips(['order_by' => 'title', 'order_direction' => 'asc']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(2);
    expect($result['data'][0]['title'])->toBe('Apple Tip');
    expect($result['data'][1]['title'])->toBe('Zebra Tip');
});

test('it can order results by published at descending', function () {
    HealthTip::create([
        'title' => 'Old Tip',
        'description' => 'Old Description',
        'content' => 'Old Content',
        'category' => 'General',
        'source' => 'Source',
        'published_at' => now()->subDays(10)
    ]);

    HealthTip::create([
        'title' => 'New Tip',
        'description' => 'New Description',
        'content' => 'New Content',
        'category' => 'General',
        'source' => 'Source',
        'published_at' => now()
    ]);

    $result = $this->service->getHealthTips(['order_by' => 'published_at', 'order_direction' => 'desc']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(2);
    expect($result['data'][0]['title'])->toBe('New Tip');
    expect($result['data'][1]['title'])->toBe('Old Tip');
});

test('it returns fallback data when no tips exist', function () {
    $result = $this->service->getHealthTips();

    expect($result['success'])->toBeFalse();
    expect($result['data'])->toBeEmpty();
    expect($result['message'])->toBe('No health tips found matching your criteria');
    expect($result['fallback'])->toBeArray();
    expect($result['fallback'])->toHaveCount(3);
});

test('it can get health tip by id', function () {
    $healthTip = HealthTip::create([
        'title' => 'Specific Tip',
        'description' => 'Specific Description',
        'content' => 'Specific Content',
        'category' => 'Specific Category',
        'source' => 'Specific Source'
    ]);

    $result = $this->service->getHealthTipById($healthTip->id);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->not->toBeNull();
    expect($result['data']['title'])->toBe('Specific Tip');
    expect($result['message'])->toBe('Health tip retrieved successfully');
});

test('it returns not found for non existent health tip id', function () {
    $result = $this->service->getHealthTipById(99999);

    expect($result['success'])->toBeFalse();
    expect($result['data'])->toBeNull();
    expect($result['message'])->toBe('Health tip not found');
});

test('it can get random health tip', function () {
    // Create multiple tips
    for ($i = 1; $i <= 5; $i++) {
        HealthTip::create([
            'title' => "Random Tip $i",
            'description' => "Random Description $i",
            'content' => "Random Content $i",
            'category' => 'Random',
            'source' => 'Random Source'
        ]);
    }

    $result = $this->service->getRandomHealthTip();

    expect($result['success'])->toBeTrue();
    expect($result['data'])->not->toBeNull();
    expect(str_contains($result['data']['title'], 'Random Tip'))->toBeTrue();
    expect($result['message'])->toBe('Random health tip retrieved successfully');
});

test('it returns fallback for random when no tips exist', function () {
    $result = $this->service->getRandomHealthTip();

    expect($result['success'])->toBeFalse();
    expect($result['data'])->toBeNull();
    expect($result['message'])->toBe('No health tips available');
    expect($result['fallback'])->toBeArray();
    expect($result['fallback'])->toHaveKey('title');
});

test('it uses cache for repeated queries', function () {
    // Create test data
    HealthTip::create([
        'title' => 'Cached Tip',
        'description' => 'Cached Description',
        'content' => 'Cached Content',
        'category' => 'Cache',
        'source' => 'Cache'
    ]);

    // First call - should hit database
    $result1 = $this->service->getHealthTips(['category' => 'Cache']);

    // Second call - should hit cache
    $result2 = $this->service->getHealthTips(['category' => 'Cache']);

    expect($result1['success'])->toBeTrue();
    expect($result2['success'])->toBeTrue();
    expect($result1['data'])->toBe($result2['data']);
});

test('it handles invalid order by gracefully', function () {
    HealthTip::create([
        'title' => 'Test Tip',
        'description' => 'Test Description',
        'content' => 'Test Content',
        'category' => 'Test',
        'source' => 'Test'
    ]);

    // Try to order by an invalid column
    $result = $this->service->getHealthTips(['order_by' => 'invalid_column']);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    // Should fall back to default ordering (published_at desc)
});

test('it can combine multiple filters', function () {
    HealthTip::create([
        'title' => 'WHO Nutrition Tip',
        'description' => 'WHO Nutrition Description',
        'content' => 'WHO Nutrition Content',
        'category' => 'Nutrition',
        'source' => 'WHO'
    ]);

    HealthTip::create([
        'title' => 'CDC Nutrition Tip',
        'description' => 'CDC Nutrition Description',
        'content' => 'CDC Nutrition Content',
        'category' => 'Nutrition',
        'source' => 'CDC'
    ]);

    HealthTip::create([
        'title' => 'WHO Exercise Tip',
        'description' => 'WHO Exercise Description',
        'content' => 'WHO Exercise Content',
        'category' => 'Exercise',
        'source' => 'WHO'
    ]);

    $result = $this->service->getHealthTips([
        'category' => 'Nutrition',
        'source' => 'WHO'
    ]);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveCount(1);
    expect($result['data'][0]['title'])->toBe('WHO Nutrition Tip');
});