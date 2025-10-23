<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRespository;
use App\Services\Task\SubtaskService;
use Illuminate\Http\Request;
use App\Http\Requests\Task\ManageSubTaskRequest;
use App\Http\Requests\Task\ManageTaskDependenciesRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;


class SubtaskController extends BaseApiController
{
    public function __construct(
        protected TaskRespository $taskRespository,
        protected SubtaskService $subtaskService
    ) {}

    public function index(Task $task): JsonResponse
    {
        $subs = $this->subtaskService->getSubtasks($task);
        return $this->successResponse($subs, 'Subtasks retrieved Successfully');
    }

    public function store(Task $task, ManageTaskDependenciesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $parent = $this->taskRespository->query()->findOrFail((int)$validated['parent_id']);
        $child = $this->taskRespository->query()->findOrFail((int)$validated['child_id']);
        $ok = $this->subtaskService->addSubtask($parent, $child);

        if (!$ok) {
            return $this->errorResponse('Cycle detectedin subtask hierarchy', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(null, 'Subtask added sucessfully');
    }

    public function destroy(Task $task, Task $subTask): JsonResponse
    {
        $this->subtaskService->removeSubtask($task, $subTask);
        return $this->noContentResponse();
    }

    public function parent(Task $task): JsonResponse
    {
        $parent = $this->subtaskService->getParent($task);
        return $this->successResponse($parent, 'parent task retrieved sucessfully');
    }

    // public function addSubtask(ManageSubTaskRequest $request): JsonResponse
    // {
    //     $validated = $request->validated();
    //     $parent = $this->taskRespository->query()->findOrFail((int)$validated['parent_id']);
    //     $child = $this->taskRespository->query()->findOrFail((int)$validated['child_id']);
    //     $ok = $this->subtaskService->addSubtask($parent, $child);

    //     if (!$ok) {
    //         return $this->errorResponse('Cycle detectedin subtask hierarchy', Response::HTTP_BAD_REQUEST);
    //     }

    //     return $this->successResponse(null, 'Subtask added sucessfully');
    // }

    // public function listSubtasks(int $taskId): JsonResponse
    // {
    //     $parent = $this->taskRespository->query()->findOrFail($taskId);
    //     $subs = $this->subtaskService->getSubtasks($parent);

    //     return $this->successResponse($subs, 'Subtasks retrieved sucessfully');
    // }

    // public function removeSubtasks(ManageSubTaskRequest $request, int $taskId): JsonResponse
    // {
    //     $validated = $request->validated();
    //     $parent = $this->taskRespository->query()->findOrFail((int)$validated['parent_id']);
    //     $child = $this->taskRespository->query()->findOrFail((int)$validated['child_id']);
    //     $this->subtaskService->removeSubtask($parent, $child);

    //     return $this->noContentResponse();
    // }

    // public function getParent(int $taskId): JsonResponse
    // {
    //     $child = $this->taskRespository->query()->findOrFail($taskId);
    //     $parent = $this->subtaskService->getParent($child);

    //     return $this->successResponse($parent, 'Parent task retrieved');
    // }

}
