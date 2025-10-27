<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Repositories\SubtaskRepository;
use Illuminate\Support\Facades\DB;

class SubtaskService
{

    public function __construct(protected SubtaskRepository $subtaskRepository)
    {
    }

    public function addSubtask(Task $parent, Task $child): bool
    {
        return DB::transaction(function () use ($parent, $child) {
            if ($this->createHeirarchyCycle($parent->id, $child->id)) {
                return false;
            }

            $this->subtaskRepository->setParent($child->id, $parent->id);
            return true;

        });
    }

    public function createHeirarchyCycle(int $parentId, int $childId): bool
    {
        // Direct self-cycle
        if ($parentId === $childId) {
            return true;
        }

        // Check if parent is reachable from child (adding parent->child would create a loop)
        $visited = [];
        $stack = [$childId];

        while (!empty($stack)) {
            $current = array_pop($stack);

            if ($current === $parentId) {
                // Parent is a descendant of child -> cycle
                return true;
            }

            if (isset($visited[$current])) {
                continue;
            }

            $visited[$current] = true;

            // Traverse downwards from the child through its existing children
            $children = Task::where('parent_id', $current)->pluck('id');
            foreach ($children as $c) {
                if (!isset($visited[$c])) {
                    $stack[] = $c;
                }
            }
        }

        return false;
    }


    public function getSubtasks(Task $parent)
    {
        return $parent->children()->get();
    }


    public function getParent(Task $child)
    {
        return $child->parent()->first();
    }

    public function getIncompleteSubtasks(int $parentId)
    {
        $incompletedCount =  Task::where('parent_id', $parentId)
            ->where('status', '!=', 'completed')
            ->get(['id', 'title', 'status']);
        return $incompletedCount === 0;
    }


    public function subtasksCompleted(int $parentId): bool
    {
        return Task::where('parent_id', $parentId)
            ->where('status', '!=', 'completed')
            ->get(['id', 'title', 'status']);
    }


    public function removeSubtask(Task $parent, Task $child): bool
    {
        return DB::transaction(function () use ($parent, $child) {
            if ($child->parent_id === $parent->id) {
                $child->parent_id = null;
                $child->save();
            }

            return true;
        });
    }
}
