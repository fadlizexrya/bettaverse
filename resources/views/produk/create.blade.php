<!DOCTYPE html>
<html>
<head>
    <title>Tambah Artikel</title>
</head>
<body>

<h1>Tambah Artikel</h1>

<form action="{{ route('artikel.store') }}" method="POST">
    @csrf

    <label>Judul</label><br>
    <input type="text" name="judul"><br><br>

    <label>Isi</label><br>
    <textarea name="isi"></textarea><br><br>

    <button type="submit">Simpan</button>
</form>

<a href="{{ route('artikel.index') }}">Kembali</a>

</body>
</html>