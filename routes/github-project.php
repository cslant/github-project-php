<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/** @var string $routePrefix */
$routePrefix = config('github-project.route_prefix', 'github-project');

Route::prefix($routePrefix)->name("{$routePrefix}.")->group(function (): void {
    Route::post('/webhook', \CSlant\GitHubProject\Actions\WebhookAction::class)
        ->name('webhook');

    Route::post('/generate-comment', \CSlant\GitHubProject\Actions\GenerateCommentAction::class)
        ->name('generate-comment');
});
