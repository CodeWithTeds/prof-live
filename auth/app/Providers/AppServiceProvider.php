<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\TagRepository;
use App\Repositories\TaskRespository;
use App\Repositories\UserRepository;

use App\Services\Auth\AuthService;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(ProjectRepository::class, function ($app) {
            return new ProjectRepository(new Project());
        });
        $this->app->bind(TaskRespository::class, function ($app) {
            return new TaskRespository(new Task());
        });

        $this->app->bind(TagRepository::class, function ($app){
            return new TagRepository(new Tag());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
