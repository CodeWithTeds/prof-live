<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRespository;
use App\Services\Task\SubtaskService;
use Illuminate\Http\Request;
use App\Http\Requests\Task\ManageSubTaskRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubtaskController extends BaseApiController
{
    public function __construct(
        protected TaskRespository $taskRespository,
        protected SubtaskService $subtaskService
    ) {}

    public function addSubtask(ManageSubTaskRequest $request): JsonResponse
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

    public function listSubtasks(int $taskId): JsonResponse
    {
        $parent = $this->taskRespository->query()->findOrFail($taskId);
        $subs = $this->subtaskService->getSubtasks($parent);

        return $this->successResponse($subs, 'Subtasks retrieved sucessfully');
    }

    public function removeSubtasks(ManageSubTaskRequest $request, int $taskId): JsonResponse
    {
        $validated = $request->validated();
        $parent = $this->taskRespository->query()->findOrFail((int)$validated['parent_id']);
        $child = $this->taskRespository->query()->findOrFail((int)$validated['child_id']);
        $this->subtaskService->removeSubtask($parent, $child);

        return $this->noContentResponse();
    }

    public function getParent(int $taskId): JsonResponse
    {
        $child = $this->taskRespository->query()->findOrFail($taskId);
        $parent = $this->subtaskService->getParent($child);

        return $this->successResponse($parent, 'Parent task retrieved');
    }


}
