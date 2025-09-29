<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Runner\DeprecationCollector\Collector;

class ProjectRepository extends Repository{

    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function updateStatus(model $project, string $status): bool
    {
        return $this->update($project, ['status' => $status]);
    }

    public function getByPriority(string $priority): Collection
    {
        return $this->model->where('priority', $priority);
    }

    public function updatePriority(model $priority, string $status): bool
    {
        return $this->update($priority, ['status' => $status]);
    }

    public function getActiveProject(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getCompletedProject(): Collection
    {
        return $this->model->where('status', 'completed')->get();
    }

    public function getOverDueProjects(): Collection
    {
        return $this->model->where('end_date', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->get();
    }

    public function getProjectByBudgetRange(float $minBudget, float $maxBudget): Collection
    {
        return $this->model->whereBetween('budget', [$minBudget, $maxBudget])->get();
    }

}
