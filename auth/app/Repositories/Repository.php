<?php

namespace App\Repositories;

use Attribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\map;

abstract class Repository
{
    /**
     *
     * Avoid Boiler Plate
     * Construction Property Promotion
     */
        public function __construct(protected Model $model) {}

    /**
     *
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->query()->with($relations)->paginate($perPage, $columns);
    }

    /**
     *
     * Find a model by its primary key
     * @return Model|null
     */
    public function find(int|string $id, array $columns = ['*'], array|string $relations = []): ?Model
    {
        return $this->query()->with($relations)->find($id, $columns);
    }

    /**
     * Create a new model
     *
     * @return Model
     */
    public function create(array $data): model
    {
        return $this->model->create($data);
    }


    public function update(Model $model, array $data): bool
    {
        return $model->update();
    }

    public function updateOrCreate(array $Attributes, array $values): Model
    {
        return $this->model->updateOrCreate($Attributes, $values);
    }

    /**
     * Delete a model
     * method signature is a blueprint of how your method look like
     *
     * Type-hinted parameters = declare the expected type of the value
     * @param Model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * class_uses_recursive check if a model have a soft delete
     *
     * Type-hinted parameters = declare the expected type of the value
     * @param Model
     */
    public function forceDelete(Model $model): ?bool
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
            // Hard delete (skip soft delete)
            return $model->forceDelete();
        }
        return null;
    }

    public function restore(Model $model): ?bool
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
            return $model->restore();
        }
        return null;
    }

    /**
     * its a anonymous function that pass a parameters called (Closure)
     */
    public function transaction(\Closure $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * method signature is a blueprint of how your method look like
     */
    public function createWithRelations(array $attributes, array $data, array $values, array $relations = []): Model
    {
        return DB::transaction(function () use ($attributes, $values, $relations) {
            $model = $this->updateOrCreate($attributes, $values);
            foreach ($relations as $relation => $relationData) {
                if (method_exists($model, $relation)) {
                    $model->relation()->updateOrCreate(
                        array_intersect_key($relationData, $attributes),
                        array_diff_key($relationData, $attributes)
                    );
                }
            }
        });
    }

    /**
     * @return Collection
     */
    public function onlyTrashed(array $columns = ['*']): Collection
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($this->model))) {
            return $this->model->onlyTrashed()->get($columns);
        }

        return collect();
    }


    /**
     * method signature is a blueprint of how your method look like
     * @return Collection
     */

    public function WithTrashed(array $columns = ['*']): Collection
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($this->model))) {
            return $this->model->WithTrashed()->get($columns);
        };

        return $this->model->get($columns);
    }
}
