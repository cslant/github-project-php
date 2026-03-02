<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Actions;

use CSlant\GitHubProject\Services\GithubService;
use CSlant\GitHubProject\Services\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class GenerateCommentAction
{
    public function __construct(
        protected readonly WebhookService $webhookService,
        protected readonly GithubService $githubService,
    ) {}

    /**
     * Generate a comment message from the webhook payload.
     *
     * @throws Throwable
     */
    public function __invoke(Request $request, bool $validate = true): JsonResponse
    {
        $startTime = microtime(true);

        try {
            if ($request->isJson()) {
                $jsonContent = $request->json();
                $payload = is_object($jsonContent) && method_exists($jsonContent, 'all')
                    ? $jsonContent->all()
                    : (array) $jsonContent;
            } else {
                $payload = json_decode($request->getContent(), true);
            }

            if (!empty($payload['payload'])) {
                $payload = $payload['payload'];
            }

            if (is_string($payload)) {
                $payload = json_decode($payload, true);
            }

            if ($validate) {
                $validationResponse = $this->webhookService->validatePayloadForComment($payload);
                if ($validationResponse !== null) {
                    return $validationResponse;
                }
            }

            $comment = $this->githubService->generateCommentMessage($payload);

            return response()->json([
                'success' => true,
                'message' => __('github-project::github-project.success.message'),
                'comment' => $comment,
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2).'ms',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2).'ms',
            ], 500);
        }
    }
}
