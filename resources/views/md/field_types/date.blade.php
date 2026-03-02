@php
    $fromValue = $fromValue ? \Carbon\Carbon::parse($fromValue)->format('Y-m-d') : null;
    $toValue = $toValue ? \Carbon\Carbon::parse($toValue)->format('Y-m-d') : null;
@endphp

@if($fromValue !== null && $toValue !== null)
    **`{{ $fieldName }}`** has been changed:
    - From **`{{ $fromValue }}`**
    - To **`{{ $toValue }}`**.
@elseif ($toValue === null)
    The value of **`{{ $fieldName }}`** has been removed.
@else
    **`{{ $fieldName }}`** has been set to **`{{ $toValue }}`**.
@endif
