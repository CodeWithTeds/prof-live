<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskRespository extends Repository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function updateStatus(Model $task, string $status): bool
    {
        return $this->update($task, ['status' => $status]);
    }

    public function getByPriority(string $priority): Collection
    {
        return $this->model->where('priority', $priority)->get();
    }

    public function updatePriority(Model $task, string $priority): bool
    {
        return $this->update($task, ['priority' => $priority]);
    }


    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getDueToday(): Collection
    {
        return $this->model->whereDate('due_date', now()->toDateString())->get();
    }

    public function getUpcoming(int $days = 7): Collection
    {
        return $this->model->whereDate('due_date', '>=', now()->toDateString())
            ->whereDate('due_date', '<=', now()->addDay($days)->toDateString())
            ->where('status', '!=', 'completed')
            ->get();
    }

    public function getOverdue(): Collection
    {
        return $this->model->whereDate('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->get();
    }

    public function removeDependency(Task $task, Task $dependsOnTaskId): bool
    {
        $task->dependencies()->detach($dependsOnTaskId->id);

        return true;
    }

    public function addDependency(Task $task, Task $dependsOnTaskId): bool
    {
        $task->dependencies()->syncWithoutDetaching([$dependsOnTaskId->id]);

        return true;
    }

    public function getDependencies(int $taskId): Collection
    {
        $task = $this->query()->findOrFail($taskId);
        return $task->dependencies()->get();
    }

    public function getDependents(int $taskId): Collection
    {
        $task = $this->query()->findOrFail($taskId);
        return $task->dependents()->get();
    }

    public function getDependencyIds(Task $task): array
    {
        return DB::table('task_dependencies')
            ->where('task_id', $task->id)
            ->pluck('depends_on_task_id')
            ->all();
    }

}
