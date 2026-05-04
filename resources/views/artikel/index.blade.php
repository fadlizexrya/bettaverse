<!DOCTYPE html>
<html>
<head>
    <title>Daftar Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Daftar Artikel</h1>

    <a href="#" class="btn btn-primary mb-3">+ Tambah Artikel</a>

    @foreach ($artikels as $artikel)
        <div class="card mb-3">
            <div class="card-body">
                <h4>{{ $artikel->judul }}</h4>
                <p>{{ $artikel->isi }}</p>

                <hr>

                <h6>Komentar:</h6>

                @foreach ($artikel->komentars as $komentar)
                    <div class="mb-2">
                        <strong>{{ $komentar->user->name ?? 'User' }}:</strong>
                        {{ $komentar->isi }}
                    </div>
                @endforeach

            </div>
        </div>
    @endforeach

</div>

</body>
</html>