<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ManageTaskDependenciesRequest;
use App\Repositories\TaskRespository;
use App\Services\Task\TaskDependencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;


class TaskDependencyController extends BaseApiController
{
    public function __construct(
        protected TaskRespository $taskRespository,
        protected TaskDependencyService $taskDependencyService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Task $task): JsonResponse
    {
        $deps = $this->taskDependencyService->getDependencies($task);
        return $this->successResponse($deps, 'dependenciesklkkl;kkko retrieved Successfully');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Task $task, ManageTaskDependenciesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $dependsOn = $this->taskRespository->query()->findOrFail((int)$validated['depends_on_task_id']);
        $ok = $this->taskDependencyService->addDependency($task, $dependsOn);

        if(!$ok){
            return $this->errorResponse('Cycle detected in task dependecies', Response::HTTP_BAD_REQUEST );
        }

        return $this->successResponse(null, 'Dependency added sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Task $dependecy): JsonResponse
    {
        $this->taskDependencyService->removeDependency($task, $dependecy);
        return $this->noContentResponse();
    }

    public function dependets(Task $task): JsonResponse
    {
        $deps = $this->taskDependencyService->getDependencies($task);
        return $this->successResponse($deps, 'Dependends retrievedsucessfully');
    }
}
