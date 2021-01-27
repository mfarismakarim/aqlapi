@component('mail::message', ['data' => $data])
# Halo {{ $data['name'] }},

Terima kasih sudah mau berdonasi untuk penggalangan {{ $data['campaign'] }}. Selanjutnya anda bisa melakukan transfer sebesar Rp {{ $data['amount'] }} ke:
@foreach ($data['banks'] as $bank)
@php 
    $item = (array) $bank
@endphp
@if($loop->index !== 0)
atau 
@endif

{{ $item['bank'] }}<br>
Nomor rekening: {{ $item['norek'] }}<br>
Atas nama: {{ $item['an'] }}<br>

@endforeach

<img src="{{$data['qrcode']}}" alt="qraqlpeduli" style="width:200px;height:200px;">

<br> Untuk konfirmasi donasi dan jika ada pertanyaan lebih lanjut dapat langsung menghubungi kami di wa.me/6282239193515.

Anda juga dapat berdonasi untuk program kepedulian lainnya melalui aqlpeduli.or.id/kepedulian.

Salam,<br>
{{ config('app.name') }}
@endcomponent
