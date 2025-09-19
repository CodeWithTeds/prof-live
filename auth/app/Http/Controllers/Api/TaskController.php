<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Repositories\TaskRespository;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseApiController;

class TaskController extends BaseApiController
{
    /**
     * Create new Controller Instance
     *
     * @param TaskRepository
     */
    public function __construct(protected TaskRespository $taskRespository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->taskRespository->update($task, $request->validated());

        return $this->successResponse($task->fresh(), 'Task Updated  Scuccessfully');
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


    public function getUpcoming(Request $request): JsonResponse
    {
        $days = $request->input('days', 7);
        $tasks = $this->taskRespository->getUpcoming($days);

        return $this->successResponse($tasks, "Upcoming tasks for next {$days} retrieved succussfully");
    }


    public function getOverdue(): JsonResponse
    {

    }
}
