<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Project;
use App\Repositories\ProjectRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
