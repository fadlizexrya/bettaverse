<?php

namespace App\Filament\Resources\MarketplacePosts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload; // Tambahkan ini untuk upload gambar
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MarketplacePostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ✅ Tampilkan nama penjual (hanya display)
                TextInput::make('penjual')
                    ->label('Penjual')
                    ->default(fn () => Auth::user()?->name)
                    ->disabled()
                    ->dehydrated(false), 

                // ✅ Hidden user_id
                TextInput::make('user_id')
                    ->default(fn () => Auth::id())
                    ->hidden(),

                // 🔄 UBAH: judul menjadi nama_cupang agar sinkron dengan DB
                TextInput::make('nama_cupang')
                    ->label('Nama Ikan Cupang')
                    ->placeholder('Contoh: Blue Rim High Quality')
                    ->required(),

                // ➕ TAMBAH: jenis_cupang
                TextInput::make('jenis_cupang')
                    ->label('Jenis Cupang')
                    ->placeholder('Contoh: Plakat / Halfmoon')
                    ->required(),

                TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('stok')
                    ->numeric()
                    ->default(1)
                    ->required(),

                // ➕ TAMBAH: no_wa
                TextInput::make('no_wa')
                    ->label('Nomor WhatsApp')
                    ->placeholder('Contoh: 08123456789')
                    ->tel() // Mengoptimalkan keyboard HP untuk nomor telpon
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Lengkap')
                    ->required(),

                // ➕ TAMBAH: foto_cupang
                FileUpload::make('foto_cupang')
                    ->label('Foto Ikan')
                    ->image() // Hanya menerima file gambar
                    ->directory('produk-images') // Akan tersimpan di storage/app/public/produk-images
                    ->disk('public'),
            ]);
    }
}
