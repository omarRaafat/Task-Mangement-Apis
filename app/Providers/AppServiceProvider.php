<?php

namespace App\Providers;

use App\Repositories\TaskRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Services\TaskService;
use App\Services\CommentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        
        // Service bindings (use bind for stateless services)
        $this->app->bind(TaskService::class, TaskService::class);
        $this->app->bind(CommentService::class, CommentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}