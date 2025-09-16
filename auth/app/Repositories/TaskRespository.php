<?php

namespace App\Repositories;
use App\Models\Task;


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

}
