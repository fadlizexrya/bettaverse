<h1>{{ $artikel->judul }}</h1>

<p>{{ $artikel->isi }}</p>

<hr>

<h3>Komentar</h3>

@forelse ($artikel->komentars as $komentar)
    <div style="margin-bottom:10px;">
        <strong>{{ $komentar->user->name ?? 'User' }}</strong>
        <p>{{ $komentar->isi_komentar }}</p>
    </div>
@empty
    <p>Belum ada komentar</p>
@endforelse