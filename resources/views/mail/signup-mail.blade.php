@component('mail::message')
# Registratie coachingsupport

Er heeft een nieuw bedrijf geregistreerd:

{{-- # {{ $details['title'] }}

{{ $details['body'] }} --}}

@component('mail::button', ['url' => '/login'])
Ga naar de site
@endcomponent

{{ config('app.name') }}
@endcomponent
