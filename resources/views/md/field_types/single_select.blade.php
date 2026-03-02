@php
    $fromColor = $fieldData['from']['color'] ?? null;
    $toColor = $fieldData['to']['color'] ?? null;
@endphp

@if($fromValue !== null && $toValue !== null)
    **`{{ $fieldName }}`** has been changed from {{ color_value_format($fromValue, $fromColor) }} to {{ color_value_format($toValue, $toColor) }}.
@elseif ($toValue === null)
    The value of **`{{ $fieldName }}`** has been removed.
@else
    **`{{ $fieldName }}`** has been set to {{ color_value_format($toValue, $toColor) }}.
@endif
