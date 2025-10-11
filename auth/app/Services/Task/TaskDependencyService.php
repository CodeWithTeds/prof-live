<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskDependencyService
{

    public function addDependency(int $taskId, int $dependsOnTaskId): bool
    {
        return DB::transaction(function () use ($taskId, $dependsOnTaskId) {
            $task = Task::findOrFail($taskId);
            $dependsOn = Task::findOrFail($dependsOnTaskId);

            // Prevent for circular dependency
            if ($this->createCycle($dependsOnTaskId, $taskId)) {
                return false;
            }

            // Add dependency without removing existing ones
            $task->dependencies()->syncWithoutDetaching([$dependsOnTaskId]);

            return true;
        });
    }

    public function getDependents(int $taskId)
    {
        $task = Task::findOrFail($taskId);
        return $task->dependencies()->get();
    }

    public function getDependencies(int $taskId)
    {
        $task = Task::findOrFail($taskId);
        return $task->dependencies()->get();
    }

    public function dependenciesCompleted(int $taskId): bool
    {
        $dependencyIds = DB::table('task_dependencies')
            ->where('task_id', $taskId)
            ->pluck('depends_on_task_id')
            ->all();

        if (empty($dependencyIds)) {
            return false;
        }
        $incompleteCount = Task::whereIn('tasks')
            ->whereIn('id', $dependencyIds)
            ->where('status', '!=', 'completed')
            ->count();

        return $incompleteCount === 0;
    }

    public function removeDependency(int $taskId, int $dependsOnTaskId): bool
    {
        return DB::transaction(function () use ($taskId, $dependsOnTaskId) {
            $task = Task::findOrFail($taskId);
            $task->dependencies()->detach($dependsOnTaskId);

            return true;
        });
    }

    /**
     * DFS = Depth-First Search.
     * DFS is a handy way to check things like “can Task B eventually reach Task A?”.
     *
     */
    private function createCycle(int $taskId, int $dependsOnTaskId): bool
    {
        // DFS to detect cycles
        $visited = [];
        $stack = [$dependsOnTaskId];


        while (!empty($stack)) {
            // If we reached the original task, cycle exists
            $current = array_pop($stack);
            if ($current === $taskId) {
                return true;
            }

            if (isset($visited[$current])) {
                continue;
            }

            $visited[$current] = true;
            $deps = DB::table('task_dependencies')->where('task_id', $current)->pluck('depends_on_task_id');
            foreach ($deps as $d) {
                if (!isset($visited[$d])) {
                    $stack[] = $d;
                };
            }
        }

        return false;
    }

    public function getIncompleteDependencies(int $taskId)
    {
        $dependencyIds = DB::table('task_dependencies')
            ->where('task_id', $taskId)
            ->pluck('depends_on_task_id');

        if ($dependencyIds->isEmpty()) {
            return collect();
        }

        return Task::whereIn('id', $dependencyIds)
            ->where('status', '!=', 'completed')
            ->get(['id', 'title', 'status']);
    }
}
