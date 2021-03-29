@component('mail::message', ['data' => $data])
# Assalamualaikum {{ $data['name'] }},

Maa syaa Allah, terima kasih sudah mau berdonasi untuk penggalangan {{ $data['campaign'] }}. 

Yuk lanjutkan kebaikan ini, dengan selanjutnya melakukan transfer sebesar Rp {{ $data['amount'] }} ke:
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

<br> Untuk konfirmasi dan pertanyaan lebih lanjut dapat langsung menghubungi kami di wa.me/6282239193515.

Wassalamualaikum,<br>
{{ config('app.name') }}
@endcomponent
