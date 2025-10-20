<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Response;
use App\Http\Requests\Project\UpdateProjectStatusRequest;

class ProjectController extends BaseApiController
{

    public function __construct(protected ProjectRepository $projectRepository) {}

    /**
     * Display a listing of the resource.x
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $projects = $this->projectRepository->paginate($perPage);

        return $this->successResponse($projects);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $project = $this->projectRepository->create($validated);
        return $this->createdResponse($project, 'Project Created Successfully');
    }

    /**
     * Display the specified resource.git push origin master:main

     */
    public function show(int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id, ['*'], ['user', 'tasks']);

        return $this->successResponse($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);

        $validated = $request->validated();

        $this->projectRepository->update($project, $validated);

        return $this->successResponse($project->fresh(), 'Project Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);

        $this->projectRepository->delete($project);

        return $this->successResponse(null, 'Project deleted successfully');
    }


    public function getByPriority(Request $request, string $priority): JsonResponse
    {
        $project = $this->projectRepository->getByPriority($priority);

        return $this->successResponse($project);
    }


    public function getByStatus(Request $request, string $status): JsonResponse
    {
        $project = $this->projectRepository->getByStatus($status);

        return $this->successResponse($project);
    }

    public function getActiveProjects(Request $request): JsonResponse
    {
        $project = $this->projectRepository->getActiveProject();

        return $this->successResponse($project);
    }

    public function getCompletedProjects(Request $request): JsonResponse
    {
        $project = $this->projectRepository->getCompletedProject();

        return $this->successResponse($project);
    }

    public function getOverdueProjects(Request $request): JsonResponse
    {
        $project = $this->projectRepository->getOverDueProjects();
        return $this->successResponse();
    }

    public function updateStatus(UpdateProjectStatusRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $project = $this->projectRepository->find($id);
        $this->projectRepository->updateStatus($project, $validated['status']);

        return $this->successResponse($project->fresh(), 'Project Status updated Successfully');
    }

    public function updatePriority(UpdateProjectStatusRequest $request, int $id): JsonResponse
    {
        $validated = $request->vadidated();

        $project = $this->projectRepository->find($id);

        $this->projectRepository->updatePriority($project, $validated['priority']);

        return $this->successResponse($project->fresh() . 'Project priority updated successfully');
    }
}
