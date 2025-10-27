<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\TaskRespository;

class TaskDependencyService
{

    public function __construct(protected TaskRespository $taskRepository)
    {
    }

    public function addDependency(Task $task, Task $dependsOnTaskId): bool
    {
        return DB::transaction(function () use ($task, $dependsOnTaskId) {

            // Prevent for circular dependency
            if ($this->createCycle($task, $dependsOnTaskId)) {
                return false;
            }

            $this->taskRepository->addDependency($task, $dependsOnTaskId);

            return true;
        });
    }

    public function getDependents(int $task)
    {
        return $this->taskRepository->getDependents($task);
    }

    public function getDependencies(Task $task)
    {
        return $this->taskRepository->getDependencies($task->id);
    }

    public function dependenciesCompleted(int $taskId): bool
    {
        $task = $this->taskRepository->query()->findOrFail($taskId);
        $dependencyIds = $this->taskRepository->getDependencyIds($task);

        if (empty($dependencyIds)) {
            return true;
        }

        $incompleteCount = Task::whereIn('id', $dependencyIds)
            ->where('status', '!=', 'completed')
            ->count();

        return $incompleteCount === 0;
    }

    public function removeDependency(Task $task, Task $dependsOnTaskId): bool
    {
        return DB::transaction(function () use ($task, $dependsOnTaskId) {
            $this->taskRepository->removeDependency($task, $dependsOnTaskId);

            return true;
        });
    }

    /**
     * DFS = Depth-First Search.
     * DFS is a handy way to check things like “can Task B eventually reach Task A?”.
     *
     */
    private function createCycle(Task $task, Task $dependsOnTaskId): bool
    {
        // DFS to detect cycles
        $visited = [];
        $stack = [$dependsOnTaskId->id];
        $targetId = $task->id;

        while (!empty($stack)) {
            // If we reached the original task, cycle exists
            $currentId = array_pop($stack);
            if ($currentId === $targetId) {
                return true;
            }

            if (isset($visited[$currentId])) {
                continue;
            }

            $visited[$currentId] = true;

            $currentTask = $this->taskRepository->query()->findOrFail($currentId);
            $deps = $this->taskRepository->getDependencyIds($currentTask);

            foreach ($deps as $dId) {
                if (!isset($visited[$dId])) {
                    $stack[] = $dId;
                }
            }
        }

        return false;
    }

    public function getIncompleteDependencies(int $taskId)
    {
        $task = $this->taskRepository->query()->findOrFail($taskId);
        $dependencyIds = $this->taskRepository->getDependencyIds($task);

        if (empty($dependencyIds)) {
            return collect();
        }

        return Task::whereIn('id', $dependencyIds)
            ->where('status', '!=', 'completed')
            ->get(['id', 'title', 'status']);
    }

}
