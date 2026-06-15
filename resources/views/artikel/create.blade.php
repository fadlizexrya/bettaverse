<!DOCTYPE html>
<html>
<head>
    <title>Tambah Artikel - BettaVerse</title>
</head>
<body>

    <h1>Tulis Artikel Baru</h1>

    <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Judul Artikel:</label><br>
        <input type="text" name="judul" required><br><br>

        <label>Foto Artikel (Opsional):</label><br>
        <input type="file" name="gambar" accept="image/*"><br><br>

        <label>Isi Artikel:</label><br>
        <textarea name="isi" rows="10" required></textarea><br><br>

        <button type="submit">Terbitkan Artikel</button>
    </form>

    <hr>
    <a href="{{ route('artikel.index') }}">Kembali ke Daftar Artikel</a>

</body>
</html>
