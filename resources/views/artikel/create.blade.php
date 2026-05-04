<h1>Tambah Artikel</h1>

<form action="/artikels" method="POST">
    @csrf

    <label>Judul:</label><br>
    <input type="text" name="judul" required><br><br>

    <label>Isi:</label><br>
    <textarea name="isi"></textarea><br><br>

    <button type="submit">Simpan</button>
</form>