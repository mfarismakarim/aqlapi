@component('mail::message')
# Assalamualaikum Wr Wb {{ $data['name'] }},

{{ $data['message'] }}

Wassalamualaikum Wr Wb,<br>
{{ config('app.name') }}
@endcomponent
