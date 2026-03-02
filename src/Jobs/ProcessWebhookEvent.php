<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessWebhookEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $eventData
     */
    public function __construct(
        protected readonly array $eventData,
    ) {}

    public function handle(): void
    {
        $nodeId = (string) ($this->eventData['projects_v2_item']['content_node_id'] ?? '');

        /** @var string $cacheKeyPrefix */
        $cacheKeyPrefix = config('github-project.comment_aggregation_cache_key', 'github-project-comment-aggregation');
        $cacheKey = "{$cacheKeyPrefix}_{$nodeId}";
        $aggregationTime = (int) config('github-project.comment_aggregation_time', 20);

        /** @var list<string> $eventMessages */
        $eventMessages = Cache::get($cacheKey, []);
        $eventMessages[] = view('github-project::md.shared.content', ['payload' => $this->eventData])->render();

        Cache::put($cacheKey, $eventMessages, now()->addSeconds($aggregationTime + 3));

        if (!Cache::has("{$cacheKey}_author")) {
            Cache::put(
                "{$cacheKey}_author",
                [
                    'name' => $this->eventData['sender']['login'] ?? 'Unknown',
                    'html_url' => $this->eventData['sender']['html_url'] ?? '#',
                ],
                now()->addSeconds($aggregationTime + 3),
            );
        }

        if (count($eventMessages) === 1) {
            ProcessAggregatedEvents::dispatch($nodeId)->delay(now()->addSeconds($aggregationTime));
        }
    }
}
