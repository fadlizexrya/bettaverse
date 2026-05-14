<!DOCTYPE html>
<html>
<head>
    <title>Marketplace BettaVerse</title>
    <style>
        .produk-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .harga { color: green; font-weight: bold; }
        .stok { color: #555; }
        .img-produk { max-width: 200px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>

    <h1>Daftar Ikan Cupang Dijual</h1>
    <a href="{{ route('produk.create') }}">+ Pasang Iklan Baru</a>
    <hr>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @foreach($produks as $produk)
        <div class="produk-card">
            @if($produk->foto_cupang)
                <img src="{{ asset('storage/' . $produk->foto_cupang) }}" class="img-produk" alt="Foto Ikan">
            @else
                <p><em>(Tidak ada foto)</em></p>
            @endif

            <h3>{{ $produk->nama_cupang }} ({{ $produk->jenis_cupang }})</h3>
            <p class="harga">Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            <p class="stok">Tersedia: {{ $produk->stok }} ekor</p>
            <p><strong>Deskripsi:</strong> {{ $produk->deskripsi }}</p>
            
            <a href="https://wa.me/{{ $produk->no_wa }}" target="_blank" style="background: #25D366; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px;">
                Chat via WA
            </a>

            <br><br>

            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin mau hapus iklan ini?')" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">
                    Hapus Iklan
                </button>
            </form>
        </div>
    @endforeach

</body>
</html>
