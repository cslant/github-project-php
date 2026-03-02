<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Jobs;

use CSlant\GitHubProject\Services\GithubService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessAggregatedEvents implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected readonly string $nodeId,
    ) {
    }

    public function handle(GithubService $githubService): void
    {
        /** @var string $cacheKeyPrefix */
        $cacheKeyPrefix = config('github-project.comment_aggregation_cache_key', 'github-project-comment-aggregation');
        $cacheKey = "{$cacheKeyPrefix}_{$this->nodeId}";

        /** @var list<string> $eventMessages */
        $eventMessages = Cache::pull($cacheKey, []);

        $message = implode("\n", $eventMessages);

        /** @var array{name: string, html_url: string} $author */
        $author = Cache::pull("{$cacheKey}_author", ['name' => 'Unknown', 'html_url' => '#']);

        $message .= view(
            'github-project::md.shared.author',
            ['name' => $author['name'], 'html_url' => $author['html_url']],
        )->render();

        $githubService->commentOnNode($this->nodeId, $message);
    }
}
