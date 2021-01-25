@component('mail::message', ['data' => $data])
# Halo {{ $data['name'] }},

Terima kasih sudah mau berdonasi untuk penggalangan {{ $data['campaign'] }}. Selanjutnya anda bisa melakukan transfer sebesar Rp {{ $data['amount'] }} ke:
@foreach ($data['banks'] as $bank)

@if($loop->index !== 0)
atau 
@endif

{{ $bank['bank_name'] }}<br>
Nomor rekening: {{ $bank['account_number'] }}<br>
Atas nama: {{ $bank['owner'] }}<br>

@endforeach

<img src="{{$data['image_url']}}" alt="W3Schools.com" style="width:200px;height:200px;">

<br> Untuk konfirmasi donasi dan jika ada pertanyaan lebih lanjut dapat langsung menghubungi kami di wa.me/6282239193515.

Anda juga dapat berdonasi untuk program kepedulian lainnya melalui aqlpeduli.or.id/kepedulian.

Salam,<br>
{{ config('app.name') }}
@endcomponent
