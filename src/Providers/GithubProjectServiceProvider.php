<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Providers;

use Illuminate\Support\ServiceProvider;

class GithubProjectServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerAssetLoading();
        $this->registerAssetPublishing();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/github-project.php', 'github-project');
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return ['github-project'];
    }

    protected function registerAssetPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../../config/github-project.php' => config_path('github-project.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../lang' => resource_path('lang/packages/github-project'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/github-project'),
        ], 'views');
    }

    protected function registerAssetLoading(): void
    {
        $routePath = __DIR__.'/../../routes/github-project.php';
        if (file_exists($routePath)) {
            $this->loadRoutesFrom($routePath);
        }

        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'github-project');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'github-project');
    }
}
