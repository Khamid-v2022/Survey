@component('mail::message')

# {{ $details['title'] }}

{!! $details['body'] !!}

@component('mail::button', ['url' => route('login')])
Ga naar de site
@endcomponent

{{-- {{ config('app.name') }} --}}
@endcomponent
