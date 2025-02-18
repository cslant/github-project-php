<?php

namespace CSlant\GitHubProject\Jobs;

use CSlant\GitHubProject\Services\GithubService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessAggregatedEvents implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $nodeId;

    protected GithubService $githubService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $nodeId, GithubService $githubService)
    {
        $this->nodeId = $nodeId;
        $this->githubService = $githubService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $commentAggregationCacheKey = config('github-project.comment_aggregation_cache_key')."_{$this->nodeId}";

        /** @var array<string, mixed> $eventMessages */
        $eventMessages = Cache::pull($commentAggregationCacheKey, []);

        if (empty($eventMessages)) {
            Cache::forget($commentAggregationCacheKey.'_author');

            return;
        }
        Log::info('ProcessAggregatedEvents: Event message: '.json_encode($eventMessages));
        $message = $this->aggregateMessages($eventMessages);
        Cache::forget($commentAggregationCacheKey);
        $author = Cache::pull($commentAggregationCacheKey.'_author', '');

        Log::info('ProcessAggregatedEvents: Author: '.json_encode($author));
        $message .= '\n\n'.view(
            'github-project::md.shared.author',
            ['name' => $author['name'], 'html_url' => $author['html_url']]
        )->render();

        Log::info('ProcessAggregatedEvents: Message: '.$message);
        $this->githubService->commentOnNode($this->nodeId, $message);
    }

    /**
     * Aggregate messages from events.
     *
     * @param  array<string, mixed>  $eventMessages
     *
     * @return string
     */
    protected function aggregateMessages(array $eventMessages): string
    {
        $messages = array_map(function ($message) {
            return $message;
        }, $eventMessages);

        return implode("\n", $messages);
    }
}
