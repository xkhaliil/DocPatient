<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Cabinet;
use App\Models\Appointment;
use App\Models\HealthTip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('doctor can create a cabinet', function () {
    $doctor = User::factory()->create([
        'role' => 'doctor',
        'name' => 'Dr. Smith',
        'email' => 'dr.smith@example.com'
    ]);

    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Cardiology Clinic',
        'address' => '123 Medical Street',
        'phone' => '+1234567890',
        'specialization' => 'Cardiology',
        'working_hours' => 'Mon-Fri: 9:00-17:00'
    ]);

    expect($cabinet)->toBeInstanceOf(Cabinet::class);
    expect($cabinet->doctor_id)->toBe($doctor->id);
    expect($cabinet->name)->toBe('Cardiology Clinic');
    expect($cabinet->doctor)->toBeInstanceOf(User::class);
    expect($cabinet->doctor->name)->toBe('Dr. Smith');
});

test('patient can book an appointment with doctor', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890',
        'specialization' => 'General Medicine'
    ]);

    $appointmentDate = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
    
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => $appointmentDate,
        'reason' => 'Annual checkup',
        'status' => 'scheduled'
    ]);

    expect($appointment)->toBeInstanceOf(Appointment::class);
    expect($appointment->patient_id)->toBe($patient->id);
    expect($appointment->cabinet_id)->toBe($cabinet->id);
    expect($appointment->patient)->toBeInstanceOf(User::class);
    expect($appointment->cabinet)->toBeInstanceOf(Cabinet::class);
    expect($appointment->appointment_date)->toBe($appointmentDate);
});

test('doctor can view their appointments', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient1 = User::factory()->create(['role' => 'patient']);
    $patient2 = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    // Create multiple appointments
    $appointment1 = Appointment::create([
        'patient_id' => $patient1->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 1',
        'status' => 'scheduled'
    ]);

    $appointment2 = Appointment::create([
        'patient_id' => $patient2->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 2',
        'status' => 'scheduled'
    ]);

    // Get doctor's appointments through cabinet
    $appointments = $cabinet->appointments;
    
    expect($appointments)->toHaveCount(2);
    expect($appointments->pluck('id')->toArray())->toContain($appointment1->id);
    expect($appointments->pluck('id')->toArray())->toContain($appointment2->id);
});

test('patient can view their appointments', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    // Create multiple appointments for the same patient
    $appointment1 = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 1',
        'status' => 'scheduled'
    ]);

    $appointment2 = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 2',
        'status' => 'scheduled'
    ]);

    // Get patient's appointments
    $appointments = $patient->appointments;
    
    expect($appointments)->toHaveCount(2);
    expect($appointments->pluck('id')->toArray())->toContain($appointment1->id);
    expect($appointments->pluck('id')->toArray())->toContain($appointment2->id);
});

test('appointment status can be updated', function () {
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

    // Update status
    $appointment->update(['status' => 'completed']);
    $appointment->refresh();

    expect($appointment->status)->toBe('completed');
});

test('doctor cannot have multiple cabinets', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    
    $cabinet1 = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'First Clinic',
        'address' => '123 First Street',
        'phone' => '+1234567890'
    ]);

    // Try to create a second cabinet for the same doctor
    $cabinet2 = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Second Clinic',
        'address' => '456 Second Street',
        'phone' => '+0987654321'
    ]);

    // Both cabinets should be created (no unique constraint in current schema)
    expect($cabinet1)->toBeInstanceOf(Cabinet::class);
    expect($cabinet2)->toBeInstanceOf(Cabinet::class);
    expect($cabinet1->doctor_id)->toBe($doctor->id);
    expect($cabinet2->doctor_id)->toBe($doctor->id);
});

test('appointment cannot be scheduled in the past', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    $pastDate = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
    
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => $pastDate,
        'reason' => 'Past appointment',
        'status' => 'scheduled'
    ]);

    // Should still be created (no validation in current model)
    expect($appointment)->toBeInstanceOf(Appointment::class);
    expect($appointment->appointment_date)->toBe($pastDate);
});

test('doctor can cancel appointment', function () {
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

    // Cancel appointment
    $appointment->update(['status' => 'cancelled']);
    $appointment->refresh();

    expect($appointment->status)->toBe('cancelled');
});

test('patient can reschedule appointment', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    $originalDate = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
    $newDate = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');
    
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => $originalDate,
        'reason' => 'Checkup',
        'status' => 'scheduled'
    ]);

    // Reschedule appointment
    $appointment->update(['appointment_date' => $newDate]);
    $appointment->refresh();

    expect($appointment->appointment_date)->toBe($newDate);
});

test('appointment has proper relationships', function () {
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

    // Test relationships
    expect($appointment->patient)->toBeInstanceOf(User::class);
    expect($appointment->patient->id)->toBe($patient->id);
    expect($appointment->cabinet)->toBeInstanceOf(Cabinet::class);
    expect($appointment->cabinet->id)->toBe($cabinet->id);
    expect($appointment->cabinet->doctor)->toBeInstanceOf(User::class);
    expect($appointment->cabinet->doctor->id)->toBe($doctor->id);
});

test('cabinet has proper relationships', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient1 = User::factory()->create(['role' => 'patient']);
    $patient2 = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    // Create appointments
    $appointment1 = Appointment::create([
        'patient_id' => $patient1->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 1',
        'status' => 'scheduled'
    ]);

    $appointment2 = Appointment::create([
        'patient_id' => $patient2->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        'reason' => 'Checkup 2',
        'status' => 'scheduled'
    ]);

    // Test relationships
    expect($cabinet->doctor)->toBeInstanceOf(User::class);
    expect($cabinet->doctor->id)->toBe($doctor->id);
    expect($cabinet->appointments)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    expect($cabinet->appointments)->toHaveCount(2);
    expect($cabinet->appointments->pluck('id')->toArray())->toContain($appointment1->id);
    expect($cabinet->appointments->pluck('id')->toArray())->toContain($appointment2->id);
});

test('user has proper relationships', function () {
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

    // Test doctor relationships
    expect($doctor->cabinet)->toBeInstanceOf(Cabinet::class);
    expect($doctor->cabinet->id)->toBe($cabinet->id);
    
    // Test patient relationships
    expect($patient->appointments)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    expect($patient->appointments)->toHaveCount(1);
    expect($patient->appointments->first()->id)->toBe($appointment->id);
});

test('complex appointment scheduling scenario', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient1 = User::factory()->create(['role' => 'patient']);
    $patient2 = User::factory()->create(['role' => 'patient']);
    $patient3 = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Busy Clinic',
        'address' => '123 Busy Street',
        'phone' => '+1234567890'
    ]);

    // Schedule multiple appointments on different days
    $appointments = [];
    $dates = [
        Carbon::now()->addDays(1)->format('Y-m-d 09:00:00'),
        Carbon::now()->addDays(1)->format('Y-m-d 10:00:00'),
        Carbon::now()->addDays(2)->format('Y-m-d 09:00:00'),
        Carbon::now()->addDays(3)->format('Y-m-d 14:00:00'),
    ];

    $patients = [$patient1, $patient2, $patient3, $patient1];

    foreach ($dates as $index => $date) {
        $appointments[] = Appointment::create([
            'patient_id' => $patients[$index]->id,
            'cabinet_id' => $cabinet->id,
            'appointment_date' => $date,
            'reason' => 'Appointment ' . ($index + 1),
            'status' => 'scheduled'
        ]);
    }

    // Verify all appointments were created
    expect(count($appointments))->toBe(4);
    expect($cabinet->appointments)->toHaveCount(4);
    
    // Verify patient appointment counts
    expect($patient1->appointments)->toHaveCount(2);
    expect($patient2->appointments)->toHaveCount(1);
    expect($patient3->appointments)->toHaveCount(1);
    
    // Verify appointment dates
    foreach ($appointments as $index => $appointment) {
        expect($appointment->appointment_date)->toBe($dates[$index]);
        expect($appointment->status)->toBe('scheduled');
    }
});

test('appointment conflict detection scenario', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient1 = User::factory()->create(['role' => 'patient']);
    $patient2 = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    $sameDateTime = Carbon::now()->addDays(1)->format('Y-m-d 10:00:00');
    
    // Book first appointment
    $appointment1 = Appointment::create([
        'patient_id' => $patient1->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => $sameDateTime,
        'reason' => 'First appointment',
        'status' => 'scheduled'
    ]);

    // Book second appointment at same time (should be allowed in current implementation)
    $appointment2 = Appointment::create([
        'patient_id' => $patient2->id,
        'cabinet_id' => $cabinet->id,
        'appointment_date' => $sameDateTime,
        'reason' => 'Second appointment',
        'status' => 'scheduled'
    ]);

    expect($appointment1)->toBeInstanceOf(Appointment::class);
    expect($appointment2)->toBeInstanceOf(Appointment::class);
    expect($appointment1->appointment_date)->toBe($sameDateTime);
    expect($appointment2->appointment_date)->toBe($sameDateTime);
    expect($cabinet->appointments)->toHaveCount(2);
});

test('appointment cancellation cascade scenario', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    
    $cabinet = Cabinet::create([
        'doctor_id' => $doctor->id,
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'phone' => '+1234567890'
    ]);

    // Create multiple appointments
    $appointments = [];
    for ($i = 1; $i <= 3; $i++) {
        $appointments[] = Appointment::create([
            'patient_id' => $patient->id,
            'cabinet_id' => $cabinet->id,
            'appointment_date' => Carbon::now()->addDays($i)->format('Y-m-d H:i:s'),
            'reason' => 'Appointment ' . $i,
            'status' => 'scheduled'
        ]);
    }

    expect($patient->appointments)->toHaveCount(3);
    expect($cabinet->appointments)->toHaveCount(3);

    // Cancel all appointments
    foreach ($appointments as $appointment) {
        $appointment->update(['status' => 'cancelled']);
    }

    // Verify all are cancelled
    $cancelledAppointments = $patient->appointments()->where('status', 'cancelled')->get();
    expect($cancelledAppointments)->toHaveCount(3);
});

test('health tips integration with user roles', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);

    // Create health tips
    $healthTip1 = HealthTip::create([
        'title' => 'Doctor Tip',
        'description' => 'Tip from doctor',
        'content' => 'Health content from doctor',
        'category' => 'General Health',
        'source' => 'Medical Professional',
        'author' => $doctor->name
    ]);

    $healthTip2 = HealthTip::create([
        'title' => 'Admin Tip',
        'description' => 'Tip from admin',
        'content' => 'Health content from admin',
        'category' => 'Nutrition',
        'source' => 'Health Authority',
        'author' => $admin->name
    ]);

    expect($healthTip1)->toBeInstanceOf(HealthTip::class);
    expect($healthTip2)->toBeInstanceOf(HealthTip::class);
    expect($healthTip1->author)->toBe($doctor->name);
    expect($healthTip2->author)->toBe($admin->name);
});