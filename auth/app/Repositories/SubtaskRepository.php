<?php
namespace App\Repositories;
use App\Repositories\Repository;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class SubtaskRepository extends Repository
{
    public function __construct(Task $model)
    {
        parent:: __construct($model);
    }

    public function setParent(int $childId, ?int $parentId): bool
    {
        $child = $this->query()->findOrFail($childId);
        $child->parent_id = $parentId;

        return (bool) $child->save();
    }


    public function removeParent(int $childId): bool
    {
        return $this->setParent($childId, null);
    }

    public function getChildren(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)->get();
    }

    public function subtasksCompleted(int $parentId): bool
    {
        $incompleteCount = $this->model
            ->where('parent_id', $parentId)
            ->where('status', '!=', 'completed')
            ->count();
        return $incompleteCount === 0;
    }



}
