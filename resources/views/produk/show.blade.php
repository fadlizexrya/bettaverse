<!DOCTYPE html>
<html>
<head>
    <title>Detail Artikel</title>
</head>
<body>

<h1>Detail Artikel</h1>

<p><b>Judul:</b> {{ $artikel->judul }}</p>
<p><b>Isi:</b> {{ $artikel->isi }}</p>

<a href="{{ route('artikel.index') }}">Kembali</a>

</body>
</html>