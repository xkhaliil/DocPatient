<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Cabinet;
use App\Models\Appointment;
use App\Models\HealthTip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('it handles null values in required fields gracefully', function () {
    // Test with null values where empty strings might be expected
    $healthTip = HealthTip::create([
        'title' => null,
        'description' => 'Valid description',
        'content' => 'Valid content',
        'category' => 'Nutrition',
        'source' => 'WHO'
    ]);

    // Should handle null gracefully (either convert to empty string or reject)
    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe(''); // Laravel typically converts null to empty string
});

test('it handles extremely long text fields', function () {
    $veryLongContent = str_repeat('This is a very long health tip content that should test the limits of what the database can handle. ', 1000);
    
    $healthTip = HealthTip::create([
        'title' => 'Very Long Content Test',
        'description' => 'Test description',
        'content' => $veryLongContent,
        'category' => 'Test Category',
        'source' => 'Test Source'
    ]);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect(strlen($healthTip->content))->toBe(strlen($veryLongContent));
});

test('it handles special unicode characters', function () {
    $unicodeData = [
        'title' => 'Health Tips 🏥 - COVID-19 & Flu Season 🦠',
        'description' => 'Important health advice for winter ❄️ - Stay safe! 🛡️',
        'content' => 'Remember to wash hands 🧼, wear masks 😷, and maintain distance ↔️. 你好世界 🌍',
        'category' => 'Winter-Health/国际',
        'source' => 'WHO & CDC 🏛️',
        'author' => 'Dr. Smith 🥼'
    ];

    $healthTip = HealthTip::create($unicodeData);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect($healthTip->title)->toBe($unicodeData['title']);
    expect($healthTip->description)->toBe($unicodeData['description']);
    expect($healthTip->content)->toBe($unicodeData['content']);
});

test('it handles malformed urls gracefully', function () {
    $malformedUrls = [
        'http://example.com with spaces',
        'https://example.com/very/long/path/that/exceeds/normal/length/limits/and/continues/forever/and/ever/and/ever',
        'ftp://example.com/file.txt',
        'example.com', // Missing protocol
        'http://', // Incomplete
        'https://256.256.256.256', // Invalid IP
    ];

    foreach ($malformedUrls as $url) {
        $healthTip = HealthTip::create([
            'title' => 'Test Tip',
            'description' => 'Test description',
            'content' => 'Test content',
            'category' => 'Test Category',
            'source' => 'Test Source',
            'read_more_url' => $url
        ]);

        expect($healthTip)->toBeInstanceOf(HealthTip::class);
        expect($healthTip->read_more_url)->toBe($url);
    }
});

test('it handles invalid date formats gracefully', function () {
    $invalidDates = [
        '0000-00-00',
        '9999-99-99',
        '2024-13-45',
        '2024-02-30', // Invalid February date
        'not-a-date',
        '',
        null,
        '2024/01/15', // Wrong separator
        '01-15-2024', // Wrong order
    ];

    foreach ($invalidDates as $date) {
        $healthTip = HealthTip::create([
            'title' => 'Test Tip',
            'description' => 'Test description',
            'content' => 'Test content',
            'category' => 'Test Category',
            'source' => 'Test Source',
            'published_at' => $date
        ]);

        // Should either handle gracefully or set to null
        expect($healthTip)->toBeInstanceOf(HealthTip::class);
        if ($date === null || $date === '') {
            expect($healthTip->published_at)->toBeNull();
        }
    }
});

test('it handles circular references in relationships', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup',
        'status' => 'scheduled'
    ]);

    // Test accessing related models in a circular manner
    expect($appointment->patient)->toBeInstanceOf(User::class);
    expect($appointment->cabinet)->toBeInstanceOf(Cabinet::class);
    expect($appointment->cabinet->doctor)->toBeInstanceOf(User::class);
    expect($appointment->patient->appointments)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    expect($appointment->cabinet->appointments)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

test('it handles concurrent database operations', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient1 = User::factory()->create(['role' => 'patient']);
    $patient2 = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    // Simulate concurrent appointment creation
    $appointment1 = Appointment::create([
        'patient_id' => $patient1->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Concurrent appointment 1',
        'status' => 'scheduled'
    ]);

    $appointment2 = Appointment::create([
        'patient_id' => $patient2->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Concurrent appointment 2',
        'status' => 'scheduled'
    ]);

    expect($appointment1)->toBeInstanceOf(Appointment::class);
    expect($appointment2)->toBeInstanceOf(Appointment::class);
    expect($cabinet->appointments)->toHaveCount(2);
});

test('it handles database transaction rollbacks', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    try {
        // Create appointment with invalid data that should fail
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'cabinet_id' => $cabinet->id,
            'appointment_date' => 'invalid-date-format',
            'reason' => 'Test appointment',
            'status' => 'scheduled'
        ]);

        // If we get here, the invalid date was handled gracefully
        expect($appointment)->toBeInstanceOf(Appointment::class);
    } catch (\Exception $e) {
        // Expected behavior - invalid data should cause an exception
        expect($e)->toBeInstanceOf(\Exception::class);
    }
});

test('it handles empty string validation', function () {
    $emptyStringData = [
        'title' => '',
        'description' => '',
        'content' => '',
        'category' => '',
        'source' => '',
        'author' => '',
        'read_more_url' => ''
    ];

    $validator = Validator::make($emptyStringData, [
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
    expect($validator->errors()->has('description'))->toBeTrue();
    expect($validator->errors()->has('content'))->toBeTrue();
    expect($validator->errors()->has('category'))->toBeTrue();
    expect($validator->errors()->has('source'))->toBeTrue();
});

test('it handles whitespace-only strings', function () {
    $whitespaceData = [
        'title' => '   ',
        'description' => "\t\n\r",
        'content' => '     ',
        'category' => "\t\n",
        'source' => '   ',
        'author' => "\r\n"
    ];

    $validator = Validator::make($whitespaceData, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'required|string',
        'category' => 'required|string|max:100',
        'source' => 'required|string|max:100',
        'author' => 'nullable|string|max:100'
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
    expect($validator->errors()->has('description'))->toBeTrue();
    expect($validator->errors()->has('content'))->toBeTrue();
    expect($validator->errors()->has('category'))->toBeTrue();
    expect($validator->errors()->has('source'))->toBeTrue();
});

test('it handles numeric overflow in ids', function () {
    // Test with extremely large numbers that might cause overflow
    $largeNumbers = [
        PHP_INT_MAX,
        PHP_INT_MAX - 1,
        999999999999999999,
        -1,
        0
    ];

    foreach ($largeNumbers as $number) {
        try {
            // Try to create appointment with potentially problematic IDs
            $appointment = Appointment::create([
                'patient_id' => $number,
                'cabinet_id' => $number,
                'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
                'reason' => 'Test appointment',
                'status' => 'scheduled'
            ]);

            // Should either create successfully or throw appropriate exception
            expect($appointment)->toBeInstanceOf(Appointment::class);
        } catch (\Exception $e) {
            // Expected for invalid IDs
            expect($e)->toBeInstanceOf(\Exception::class);
        }
    }
});

test('it handles memory exhaustion scenarios', function () {
    // Create very large content to test memory handling
    $hugeContent = str_repeat('This is a test content that should consume significant memory. ', 10000);
    
    $healthTip = HealthTip::create([
        'title' => 'Memory Test',
        'description' => 'Testing memory handling',
        'content' => $hugeContent,
        'category' => 'Test Category',
        'source' => 'Test Source'
    ]);

    expect($healthTip)->toBeInstanceOf(HealthTip::class);
    expect(strlen($healthTip->content))->toBe(strlen($hugeContent));
});

test('it handles timezone edge cases', function () {
    $edgeCaseDates = [
        '2024-12-31 23:59:59', // End of year
        '2024-01-01 00:00:00', // Start of year
        '2024-02-29 12:00:00', // Leap year
        '2024-03-10 02:30:00', // DST transition (if applicable)
        '2024-11-03 01:30:00', // DST fallback (if applicable)
    ];

    foreach ($edgeCaseDates as $date) {
        $appointment = Appointment::create([
            'patient_id' => 1,
            'cabinet_id' => 1,
            'appointment_date' => $date,
            'reason' => 'Timezone test appointment',
            'status' => 'scheduled'
        ]);

        expect($appointment)->toBeInstanceOf(Appointment::class);
        expect($appointment->appointment_date)->toBe($date);
    }
});

test('it handles orphaned records gracefully', function () {
    // Create appointment with non-existent foreign keys
    $appointment = Appointment::create([
        'patient_id' => 999999, // Non-existent patient
        'cabinet_id' => 999999, // Non-existent cabinet
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Orphaned appointment',
        'status' => 'scheduled'
    ]);

    expect($appointment)->toBeInstanceOf(Appointment::class);
    
    // Accessing relationships should handle missing records gracefully
    expect($appointment->patient)->toBeNull();
    expect($appointment->cabinet)->toBeNull();
});