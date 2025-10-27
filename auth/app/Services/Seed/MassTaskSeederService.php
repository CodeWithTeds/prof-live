<?php

namespace App\Services\Seed;

use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class MassTaskSeederService
{
    /**
     * Seed tasks in high-throughput batches.
     *
     * @param int $count Total number of rows to insert
     * @param int $batchSize Number of rows per insert batch
     * @param int $userId Valid user id to assign to tasks
     * @return void
     */
    public function seed(int $count, int $batchSize, int $userId): void
    {
        if ($count <= 0 || $batchSize <= 0) {
            return;
        }

        // Performance tuning for large inserts
        DB::disableQueryLog();
        // Speed up bulk inserts when foreign keys are present
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $now = CarbonImmutable::now();

        $batches = intdiv($count, $batchSize);
        $remainder = $count % $batchSize;

        for ($b = 0; $b < $batches; $b++) {
            $rows = $this->buildRows($batchSize, $b * $batchSize, $userId, $now);
            DB::transaction(function () use ($rows) {
                DB::table('tasks')->insert($rows);
            });
        }

        if ($remainder > 0) {
            $rows = $this->buildRows($remainder, $batches * $batchSize, $userId, $now);
            DB::transaction(function () use ($rows) {
                DB::table('tasks')->insert($rows);
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Build an array of task rows for a single batch.
     *
     * @param int $size Number of rows to build
     * @param int $offset Starting index offset
     * @param int $userId User id to assign
     * @param CarbonImmutable $now Timestamp for created_at/updated_at
     * @return array<int, array<string, mixed>>
     */
    protected function buildRows(int $size, int $offset, int $userId, CarbonImmutable $now): array
    {
        $rows = [];
        for ($i = 0; $i < $size; $i++) {
            $n = $offset + $i + 1;
            $rows[] = [
                'title' => 'Task #' . $n,
                'description' => null,
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => null,
                'user_id' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        return $rows;
    }
}