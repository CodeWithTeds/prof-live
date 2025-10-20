<?php


namespace App\Repositories;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagRepository extends Repository
{
    public function __construct(Tag $model)
    {
        parent:: __construct($model);
    }

    public function findByName(string $name): ?Tag
    {
        return $this->model()->where('name', $name)->first();
    }

    public function getAll(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

}
