@component('mail::message')
# Assalamualaikum Wr Wb {{ $data['name'] }},

Donasi Anda untuk kepedulian {{ $data['campaign'] }} telah kami terima sebesar Rp {{ $data['amount'] }}.

Terima kasih atas donasi yang telah diberikan, semoga menjadi amal jariyah dan mendapat keberkahan atas apa yang Anda berikan.

Yuk teruskan rantai kebaikan ini dengan mengajak teman Anda ikut berdonasi melalui aqlpeduli.or.id/kepedulian.

Wassalamualaikum Wr Wb,<br>
{{ config('app.name') }}
@endcomponent
