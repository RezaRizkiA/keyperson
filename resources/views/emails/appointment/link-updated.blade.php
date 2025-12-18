<!DOCTYPE html>
<html>

<head>
 <title>Meeting Link Updated</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

 <h2>Halo, {{ $appointment->user->name }}!</h2>

 <p>
  Expert <strong>{{ $appointment->expert->user->name }}</strong> baru saja memperbarui detail lokasi/link
  untuk sesi konsultasi Anda.
 </p>

 <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
  <p style="margin: 0; font-size: 14px; color: #666;">Topik Sesi:</p>
  <p style="margin: 5px 0 0; font-weight: bold;">{{ $appointment->skill->name }}</p>

  <br>

  <p style="margin: 0; font-size: 14px; color: #666;">Waktu:</p>
  <p style="margin: 5px 0 0; font-weight: bold;">
   {{ \Carbon\Carbon::parse($appointment->date_time)->format('l, d F Y - H:i') }} WIB
  </p>
 </div>

 <p>Silakan klik tombol di bawah ini untuk bergabung saat sesi dimulai:</p>

 <a href="{{ $appointment->location_url }}"
  style="background-color: #7c3aed; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
  Join Meeting
 </a>

 <p style="margin-top: 20px;">
  Atau copy link berikut:<br>
  <a href="{{ $appointment->location_url }}">{{ $appointment->location_url }}</a>
 </p>

 <p>Terima kasih,<br>Tim Aplikasi Kami</p>

</body>

</html>