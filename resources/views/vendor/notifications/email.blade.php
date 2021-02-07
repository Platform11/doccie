@component('mail::message', ['logo' => $logo ?? asset('/images/doccie-logo.png'), 'alt' => $alt ?? 'Doccie'])
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('messages.hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color ?? '30BBB2';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color ?? '30BBB2'])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('messages.regards'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "messages.email_button_not_working",  [
        'actionText' => $actionText,
    ])
   
<span class="break-all"><a style="color: #{{$color ?? '30BBB2'}}" href="{{ $actionUrl }}">{{ $displayableActionUrl }}</a></span>
@endslot
@endisset
@endcomponent
