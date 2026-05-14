<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Marketplace - BettaVerse</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 20px auto; padding: 20px; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        textarea { height: 100px; resize: vertical; }
        button { 
            background-color: #3498db; color: white; padding: 12px 20px; border: none; 
            border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%;
        }
        button:hover { background-color: #2980b9; }
        .back-link { display: inline-block; margin-top: 20px; color: #7f8c8d; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Jual Ikan Cupang Baru</h1>

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nama">Nama Ikan Cupang:</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Blue Rim High Grade" required>
        </div>

        <div class="form-group">
            <label for="jenis">Jenis Cupang:</label>
            <input type="text" id="jenis" name="jenis" placeholder="Contoh: Plakat, Halfmoon, Giant" required>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp):</label>
            <input type="number" id="harga" name="harga" placeholder="Contoh: 150000" required>
        </div>

        <div class="form-group">
            <label for="stok">Stok Ikan:</label>
            <input type="number" id="stok" name="stok" value="1" min="1" required>
        </div>

        <div class="form-group">
            <label for="no_wa">Nomor WhatsApp (Aktif):</label>
            <input type="text" id="no_wa" name="no_wa" placeholder="Contoh: 08123456789" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Detail:</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan kondisi ikan, usia, atau catatan lainnya..." required></textarea>
        </div>

        <div class="form-group">
            <label for="gambar">Foto Ikan:</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
            <small>Format: JPG, PNG, JPEG (Maks. 2MB)</small>
        </div>

        <button type="submit">Pasang Iklan Marketplace</button>
    </form>

    <a href="{{ route('produk.index') }}" class="back-link">← Kembali ke Marketplace</a>

</body>
</html>
