<?php

use App\Services\SequenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Clear all sequences so each test starts clean
    DB::table('sequences')->truncate();
});

// G-3: SequenceService::next()
test('next() seeds a new sequence starting at 1', function () {
    $service = new SequenceService();

    $value = DB::transaction(fn () => $service->next('test_seq'));

    expect($value)->toBe(1);
    expect(DB::table('sequences')->where('name', 'test_seq')->value('value'))->toBe(1);
});

test('next() increments an existing sequence', function () {
    DB::table('sequences')->insert(['name' => 'invoice_number', 'value' => 5]);
    $service = new SequenceService();

    $value = DB::transaction(fn () => $service->next('invoice_number'));

    expect($value)->toBe(6);
});

test('sequential next() calls return strictly incrementing values', function () {
    $service = new SequenceService();

    $results = [];
    for ($i = 0; $i < 5; $i++) {
        $results[] = DB::transaction(fn () => $service->next('counter'));
    }

    expect($results)->toBe([1, 2, 3, 4, 5]);
});

test('next() uses separate sequences for different names', function () {
    $service = new SequenceService();

    $a = DB::transaction(fn () => $service->next('seq_a'));
    $b = DB::transaction(fn () => $service->next('seq_b'));
    $a2 = DB::transaction(fn () => $service->next('seq_a'));

    expect($a)->toBe(1);
    expect($b)->toBe(1);
    expect($a2)->toBe(2);
});
