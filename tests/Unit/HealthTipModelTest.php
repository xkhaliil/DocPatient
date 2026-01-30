<?php

namespace Tests\Unit;

use App\Models\HealthTip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can create a health tip with valid data', function () {
    $healthTipData = [
        'title' => 'Test Health Tip',
        'description' => 'This is a test description',
        'content' => 'This is the main content of the health tip',
        'category' => 'Nutrition',
        'source' => 'Medical Journal',
        'author' => 'Dr. John Doe',
        'published_at' => now(),
        'read_more_url' => 'https://example.com/health-tip'
    ];

    $healthTip = HealthTip::create($healthTipData);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe('Test Health Tip');
    expect($healthTip->description)->toBe('This is a test description');
    expect($healthTip->content)->toBe('This is the main content of the health tip');
    expect($healthTip->category)->toBe('Nutrition');
    expect($healthTip->source)->toBe('Medical Journal');
    expect($healthTip->author)->toBe('Dr. John Doe');
    expect($healthTip->published_at)->not->toBeNull();
    expect($healthTip->read_more_url)->toBe('https://example.com/health-tip');
});

test('it can create a health tip with minimal required data', function () {
    $healthTipData = [
        'title' => 'Minimal Health Tip',
        'description' => 'Minimal description',
        'content' => 'Minimal content',
        'category' => 'General',
        'source' => 'Unknown'
    ];

    $healthTip = HealthTip::create($healthTipData);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe('Minimal Health Tip');
    expect($healthTip->description)->toBe('Minimal description');
    expect($healthTip->content)->toBe('Minimal content');
    expect($healthTip->category)->toBe('General');
    expect($healthTip->source)->toBe('Unknown');
    expect($healthTip->author)->toBeNull();
    expect($healthTip->published_at)->toBeNull();
    expect($healthTip->read_more_url)->toBeNull();
});

test('it has fillable attributes', function () {
    $healthTip = new HealthTip();
    
    $expectedFillable = [
        'title',
        'description',
        'content',
        'category',
        'source',
        'author',
        'published_at',
        'read_more_url',
    ];

    expect($healthTip->getFillable())->toBe($expectedFillable);
});

test('it casts published_at to datetime', function () {
    $healthTip = HealthTip::create([
        'title' => 'Test Tip',
        'description' => 'Test Description',
        'content' => 'Test Content',
        'category' => 'Test',
        'source' => 'Test Source',
        'published_at' => '2024-01-15 10:30:00'
    ]);

    expect($healthTip->published_at)->toBeInstanceOf(\Carbon\Carbon::class);
    expect($healthTip->published_at->format('Y-m-d H:i:s'))->toBe('2024-01-15 10:30:00');
});

test('it can retrieve all health tips', function () {
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'Description 1',
        'content' => 'Content 1',
        'category' => 'Category 1',
        'source' => 'Source 1'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'Description 2',
        'content' => 'Content 2',
        'category' => 'Category 2',
        'source' => 'Source 2'
    ]);

    $healthTips = HealthTip::all();

    expect($healthTips)->toHaveCount(2);
    expect($healthTips->first()->title)->toBe('Tip 1');
    expect($healthTips->last()->title)->toBe('Tip 2');
});

test('it can find a specific health tip by id', function () {
    $createdTip = HealthTip::create([
        'title' => 'Specific Tip',
        'description' => 'Specific Description',
        'content' => 'Specific Content',
        'category' => 'Specific Category',
        'source' => 'Specific Source'
    ]);

    $foundTip = HealthTip::find($createdTip->id);

    expect($foundTip)->not->toBeNull();
    expect($foundTip->id)->toBe($createdTip->id);
    expect($foundTip->title)->toBe('Specific Tip');
});

test('it returns null when finding non existent health tip', function () {
    $nonExistentTip = HealthTip::find(99999);

    expect($nonExistentTip)->toBeNull();
});

test('it can update a health tip', function () {
    $healthTip = HealthTip::create([
        'title' => 'Original Title',
        'description' => 'Original Description',
        'content' => 'Original Content',
        'category' => 'Original Category',
        'source' => 'Original Source'
    ]);

    $healthTip->update([
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ]);

    expect($healthTip->title)->toBe('Updated Title');
    expect($healthTip->description)->toBe('Updated Description');
    expect($healthTip->content)->toBe('Original Content'); // Unchanged
});

test('it can delete a health tip', function () {
    $healthTip = HealthTip::create([
        'title' => 'To Be Deleted',
        'description' => 'Delete me',
        'content' => 'Delete this content',
        'category' => 'Delete Category',
        'source' => 'Delete Source'
    ]);

    $tipId = $healthTip->id;
    
    $healthTip->delete();

    expect(HealthTip::find($tipId))->toBeNull();
    expect(HealthTip::where('id', $tipId)->count())->toBe(0);
});

test('it can filter by category', function () {
    HealthTip::create([
        'title' => 'Nutrition Tip',
        'description' => 'Nutrition Description',
        'content' => 'Nutrition Content',
        'category' => 'Nutrition',
        'source' => 'Nutrition Source'
    ]);

    HealthTip::create([
        'title' => 'Exercise Tip',
        'description' => 'Exercise Description',
        'content' => 'Exercise Content',
        'category' => 'Exercise',
        'source' => 'Exercise Source'
    ]);

    HealthTip::create([
        'title' => 'Another Nutrition Tip',
        'description' => 'Another Nutrition Description',
        'content' => 'Another Nutrition Content',
        'category' => 'Nutrition',
        'source' => 'Another Nutrition Source'
    ]);

    $nutritionTips = HealthTip::where('category', 'Nutrition')->get();

    expect($nutritionTips)->toHaveCount(2);
    expect($nutritionTips->first()->title)->toBe('Nutrition Tip');
    expect($nutritionTips->last()->title)->toBe('Another Nutrition Tip');
});

test('it can filter by source', function () {
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

    $whoTips = HealthTip::where('source', 'WHO')->get();

    expect($whoTips)->toHaveCount(1);
    expect($whoTips->first()->title)->toBe('WHO Tip');
});

test('it can get distinct categories', function () {
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'Description 1',
        'content' => 'Content 1',
        'category' => 'Nutrition',
        'source' => 'Source 1'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'Description 2',
        'content' => 'Content 2',
        'category' => 'Exercise',
        'source' => 'Source 2'
    ]);

    HealthTip::create([
        'title' => 'Tip 3',
        'description' => 'Description 3',
        'content' => 'Content 3',
        'category' => 'Nutrition',
        'source' => 'Source 3'
    ]);

    $categories = HealthTip::distinct()->pluck('category')->sort()->values();

    expect($categories)->toHaveCount(2);
    expect($categories->toArray())->toBe(['Exercise', 'Nutrition']);
});

test('it can get distinct sources', function () {
    HealthTip::create([
        'title' => 'Tip 1',
        'description' => 'Description 1',
        'content' => 'Content 1',
        'category' => 'Category 1',
        'source' => 'WHO'
    ]);

    HealthTip::create([
        'title' => 'Tip 2',
        'description' => 'Description 2',
        'content' => 'Content 2',
        'category' => 'Category 2',
        'source' => 'CDC'
    ]);

    HealthTip::create([
        'title' => 'Tip 3',
        'description' => 'Description 3',
        'content' => 'Content 3',
        'category' => 'Category 3',
        'source' => 'WHO'
    ]);

    $sources = HealthTip::distinct()->pluck('source')->sort()->values();

    expect($sources)->toHaveCount(2);
    expect($sources->toArray())->toBe(['CDC', 'WHO']);
});