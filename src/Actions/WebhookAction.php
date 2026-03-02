<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Actions;

use CSlant\GitHubProject\Services\GithubService;
use CSlant\GitHubProject\Services\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class WebhookAction
{
    public function __construct(
        protected readonly WebhookService $webhookService,
        protected readonly GithubService $githubService,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->webhookService->eventRequestApproved($request)) {
            return response()->json(['message' => __('github-project::github-project.error.event.denied')], 403);
        }

        /** @var array<string, mixed> $payload */
        $payload = json_decode($request->getContent(), true);

        $validationResponse = $this->webhookService->validatePayload($payload);
        if ($validationResponse !== null) {
            return $validationResponse;
        }

        $this->githubService->handleComment($payload);

        return response()->json(['message' => __('github-project::github-project.success.message')]);
    }
}
