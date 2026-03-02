<?php

declare(strict_types=1);

namespace CSlant\GitHubProject\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookService
{
    public function eventRequestApproved(Request $request): bool
    {
        $event = $request->header('X-GitHub-Event', '');

        return $this->eventApproved($event);
    }

    protected function eventApproved(string $event): bool
    {
        return str_contains($event, 'project');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function isActionPresent(array $payload): bool
    {
        return isset($payload['action']);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function hasValidNodeAndFieldData(array $payload): bool
    {
        return isset($payload['projects_v2_item']['content_node_id'], $payload['changes']['field_value']);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function hasFieldTemplate(array $payload): bool
    {
        $fieldType = $payload['changes']['field_value']['field_type'] ?? '';

        return view()->exists('github-project::md.field_types.'.$fieldType);
    }

    protected function createErrorResponse(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json(['message' => __($message)], $statusCode);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function validatePayload(array $payload): ?JsonResponse
    {
        $commentValidation = $this->validatePayloadForComment($payload);
        if ($commentValidation !== null) {
            return $commentValidation;
        }

        if (!$this->isStatusCommentEnabled($payload)) {
            return $this->createErrorResponse('github-project::github-project.error.event.status_comment_disabled');
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function validatePayloadForComment(array $payload): ?JsonResponse
    {
        if (!$this->isActionPresent($payload)) {
            return $this->createErrorResponse('github-project::github-project.error.event.action_not_found', 404);
        }

        if (!$this->hasValidNodeAndFieldData($payload)) {
            return $this->createErrorResponse('github-project::github-project.error.event.missing_fields');
        }

        if (!$this->hasFieldTemplate($payload)) {
            return $this->createErrorResponse('github-project::github-project.error.event.missing_field_template');
        }

        return null;
    }

    /**
     * Check if the field name is "Status" and if status comments are enabled.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function isStatusCommentEnabled(array $payload): bool
    {
        $fieldName = $payload['changes']['field_value']['field_name'] ?? '';

        return $fieldName !== 'Status' || (bool) config('github-project.enable_status_comment');
    }
}
