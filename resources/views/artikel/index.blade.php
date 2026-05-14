<!DOCTYPE html>
<html>
<head>
    <title>Blog BettaVerse - Tips & Artikel</title>
    <style>
        .artikel-box { border-bottom: 1px solid #eee; padding: 20px 0; }
        .judul { color: #2c3e50; text-decoration: none; font-size: 24px; font-weight: bold; }
        .judul:hover { color: #3498db; }
        .meta { color: #7f8c8d; font-size: 13px; margin-bottom: 10px; }
        .ringkasan { color: #34495e; line-height: 1.6; }
        .btn-baca { display: inline-block; margin-top: 10px; color: #3498db; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Artikel & Tips Cupang</h1>
    <a href="{{ route('artikel.create') }}">+ Tulis Artikel Baru</a>
    <hr>

    @if(session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    @foreach($artikels as $artikel)
        <div class="artikel-box">
            <a href="/artikel/{{ $artikel->slug }}" class="judul">{{ $artikel->judul }}</a>
            
            <div class="meta">
                Diposting pada: {{ $artikel->created_at->format('d M Y') }} 
                | Oleh: {{ $artikel->user->name ?? 'Admin' }}
            </div>

            <p class="ringkasan">
                {{ $artikel->ringkasan }}...
            </p>

            <a href="/artikel/{{ $artikel->slug }}" class="btn-baca">Baca Selengkapnya →</a>
            
            <br><br>
            <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus artikel ini?')" style="background:none; border:none; color:red; cursor:pointer; font-size: 12px;">
                    [Hapus Artikel]
                </button>
            </form>
        </div>
    @endforeach

</body>
</html>
