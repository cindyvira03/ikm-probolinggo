<h2>{{ $mailData['title'] }}</h2>

<p>Halo {{ $mailData['ikm_nama'] }},</p>

<p>Ada pesanan baru yang sudah mengunggah bukti pembayaran.</p>

<hr>

<b>ID Pesanan:</b> {{ $mailData['pesanan_id'] }} <br>
<b>Total Bayar:</b> Rp {{ number_format($mailData['total_bayar'], 0, ',', '.') }} <br>
<b>Metode:</b> {{ $mailData['metode_pengiriman'] }} <br>
<b>Status Pesanan:</b> {{ $mailData['status_pesanan'] }} <br>
<b>Status Pembayaran:</b> {{ $mailData['status_pembayaran'] }}

<br><br>

<b>Bukti Transfer:</b><br>
<img src="{{ $mailData['bukti_transfer_url'] }}" width="300">

<hr>

<p>Silakan login ke dashboard IKM untuk memvalidasi pembayaran.</p>