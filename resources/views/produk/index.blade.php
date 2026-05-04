<!DOCTYPE html>
<html>
<head>
    <title>Data Artikel</title>
</head>
<body>

<h1>Data Artikel</h1>

<a href="{{ route('artikel.create') }}">+ Tambah Artikel</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Isi</th>
        <th>Aksi</th>
    </tr>

    @foreach($artikels as $key => $artikel)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $artikel->judul }}</td>
        <td>{{ $artikel->isi }}</td>
        <td>
            <a href="{{ route('artikel.edit', $artikel->id) }}">Edit</a>

            <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>

</body>
</html>