<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SequenceService
{
    /**
     * Atomically increment and return the next value for the given sequence.
     * Must be called within a DB transaction.
     */
    public function next(string $name): int
    {
        $sequence = DB::table('sequences')
            ->where('name', $name)
            ->lockForUpdate()
            ->first();

        if (!$sequence) {
            DB::table('sequences')->insert(['name' => $name, 'value' => 1]);
            return 1;
        }

        $next = $sequence->value + 1;
        DB::table('sequences')->where('name', $name)->update(['value' => $next]);

        return $next;
    }
}
