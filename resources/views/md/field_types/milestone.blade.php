@if($fromValue !== null && $toValue !== null)
    **`{{ $fieldName }}`** has been updated:
    - From: **`{{ $fromValue['title'] }}`**
    - To: **`{{ $toValue['title'] }}`**
@elseif ($toValue === null)
    **`{{ $fieldName }}`** has been removed from the issue.
@else
    **`{{ $fieldName }}`** has been set to **`{{ $toValue['title'] }}`**.
@endif

@if(($toValue['due_on'] ?? null) && ($toValue['due_on'] !== ($fromValue['due_on'] ?? null)))
    - Due date: {{ format_date($toValue['due_on'], 'Y-m-d') }}
@endif
