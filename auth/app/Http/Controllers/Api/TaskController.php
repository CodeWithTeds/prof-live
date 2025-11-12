<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Repositories\TaskRespository;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Request\Task\ManageTaskDependencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\Task\TaskDependencyService;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Task\FilterTaskRequest;
use App\Http\Requests\Task\GetTasksByUserRequest;
use App\Http\Requests\Task\ManageSubTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Task\SubtaskService;




class TaskController extends BaseApiController
{
    /**
     * Create new Controller Instance
     *
     * @param TaskRepository
     */
    public function __construct(
        protected TaskRespository $taskRespository,
        protected SubtaskService $subtaskService,
        protected TaskDependencyService $taskDependencyService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(FilterTaskRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? $request->input('per_page'));
        $tasks = $this->taskRespository->paginate($perPage);

        return $this->successResponse($tasks, 'Tasks Retrieved Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        if (!isset($data['user_id']) && Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $task = $this->taskRespository->create($data);

        return $this->createdResponse($task, 'Task created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        return $this->successResponse($task, 'Task retrieved Successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $task = Task::withTrashed()->findorFail($id);
        $this->taskRespository->restore($task);

        return $this->successResponse($task->fresh(), 'Task restored succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->taskRespository->update($task, $request->validated());

        return $this->successResponse($task->fresh(), 'Task Updated  Scuccessfully');
    }

    public function getByUser(GetTasksByUserRequest $request): JsonResponse
    {
        $userId = (int) $request->validated()['user_id'];
        $tasks = $this->taskRespository->getByUser($userId);

        return $this->successResponse($tasks, "Task for user '{$userId}' retrived successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->taskRespository->delete($task);

        return $this->successResponse(null, 'Task Deleted Successfully');
    }

    public function trashed(): JsonResponse
    {
        $tasks = $this->taskRespository->onlyTrashed();

        return $this->successResponse(null, 'trashed tasks retrieved successfully');
    }

    public function getByStatus(string $status): JsonResponse
    {
        $tasks = $this->taskRespository->getByStatus($status);

        return $this->successResponse($tasks, "Tasks with status '{$status}' retrived successfully");
    }

    public function getDueToday(): JsonResponse
    {
        $tasks = $this->taskRespository->getDueToday();

        return $this->successResponse($tasks, 'Tasks due today retrieved succussfully');
    }

    public function getByPriority(string $priority): JsonResponse
    {
        $tasks = $this->taskRespository->getByPriority($priority);

        return $this->successResponse($tasks, "Tasks with priority '{$priority}' retrieved succussfully");
    }

    // public function getByPriority1(string $priority): JsonResponse
    // {
    //     return $this->successResponse($this->taskRespository->getByPriority($priority), "Tasks with priority '{$priority}' retrieved successfully");
    // }


    public function getUpcoming(Request $request): JsonResponse
    {
        $days = $request->input('days', 7);
        $tasks = $this->taskRespository->getUpcoming($days);

        return $this->successResponse($tasks, "Upcoming tasks for next {$days} retrieved succussfully");
    }


    public function getOverdue(): JsonResponse
    {
        $tasks = $this->taskRespository->getOverdue();

        return $this->successResponse($tasks, 'overdue tasks retrieved successfully');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $newStatus = $request->validated()['status'];

        if (in_array($newStatus, ['in_process', 'completed'])) {

            if (!$this->taskDependencyService->dependenciesCompleted($task->id)) {
                $incomplete = $this->taskDependencyService->getIncompleteDependencies($task->id);
                return $this->errorResponse('Task has incomplete dependencies', Response::HTTP_NOT_FOUND, ['dependencies => incomplete']);
            }
        }

        $this->taskRespository->updateStatus($task, $newStatus);
        return $this->successResponse($task->fresh(), 'Task status updated Successfully');
    }

    public function updatePriority(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $this->taskRespository->updatePriority($task, $request->validated()['priority']);

        return $this->successResponse($task->fresh(), 'Task priority updated successfully');
    }
}
