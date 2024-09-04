<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnakSakitResource\Pages;
use App\Filament\Resources\AnakSakitResource\RelationManagers;
use App\Models\AnakSakit;
use App\Models\Penyakit;
use App\Models\Puskesmas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnakSakitResource extends Resource
{
    protected static ?string $model = AnakSakit::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-frown';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nama_anak')->label('Nama anak')->required(),
            Forms\Components\Select::make('usia')
                ->label('Usia')
                ->options([
                    '1-23' => '1-23 bulan',
                    '24-36' => '24-36 bulan',
                    '37-48' => '37-48 bulan',
                    '49-60' => '49-60 bulan',
                ])
                ->required(),
            Forms\Components\TextInput::make('tinggi_badan')->label('Tinggi badan (cm)')->required(),
            Forms\Components\TextInput::make('berat_badan')->label('Berat badan (kg)')->required(),
            Forms\Components\Select::make('berat_lahir')
                ->label('Riwayat kelahiran anak')
                ->options([
                    'normal' => 'Normal (Lebih dari 2,5 kg)',
                    'rendah' => 'Rendah (Kurang dari 2,5 kg)',
                ])
                ->required(),
            Forms\Components\Select::make('ibu_bekerja')
                ->label('Apakah Ibu bekerja')
                ->options([
                    1 => 'Ya',
                    0 => 'Tidak',
                ])
                ->required(),
            Forms\Components\Select::make('pendidikan_ibu')
                ->label('Pendidikan terakhir ibu')
                ->options([
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'Sarjana' => 'Sarjana',
                ])
                ->required(),
            Forms\Components\Select::make('orang_tua_merokok')
                ->label('Apakah orang tua merokok')
                ->options([
                    1 => 'Ya',
                    0 => 'Tidak',
                ])
                ->required(),
            // Forms\Components\Select::make('penyakit_penyerta')
            //     ->label('Penyakit penyerta')
            //     ->multiple()
            //     ->relationship('penyakit_anak', 'penyakit_id')
            //     ->options(function () {
            //         return Penyakit::where('jenis_penyakit', 'penyerta')->pluck( 'nama_penyakit', 'id');
            //     })
            //     ->preload(),

            // Forms\Components\Select::make('penyakit_komplikasi')
            //     ->label('Penyakit komplikasi kehamilan Ibu')
            //     ->multiple()
            //     ->relationship('penyakit_anak', 'penyakit_id')
            //     ->options(function () {
            //         return Penyakit::where('jenis_penyakit', 'komplikasi')->pluck( 'nama_penyakit', 'id');
            //     })
            //     ->preload(),
            Forms\Components\Select::make('keluarga_id')->relationship('keluarga', 'nama_lengkap')->required()->label('Keluarga dari')->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->role == 'operator' ? AnakSakit::with('keluarga')->whereHas('keluarga', function ($query) {
                    $query->where('puskesmas_id', auth()->user()->puskesmas_id);
                }) : AnakSakit::query(),
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_anak')->searchable(),
                Tables\Columns\TextColumn::make('usia')->formatStateUsing(function ($state) {
                    return $state . ' bulan';
                }),
                Tables\Columns\TextColumn::make('keluarga.nama_lengkap')->label('Keluarga'),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('tinggi_badan')->formatStateUsing(function ($state) {
                    return $state . ' cm';
                }),
                Tables\Columns\TextColumn::make('berat_badan')->formatStateUsing(function ($state) {
                    return $state . ' kg';
                }),
                Tables\Columns\TextColumn::make('berat_lahir')
                    ->label('Riwayat kelahiran')
                    ->formatStateUsing(function ($state) {
                        return $state == 'normal' ? 'Normal (Lebih dari 2,5 kg)' : 'Rendah (Kurang dari 2,5 kg)';
                    }),
                Tables\Columns\TextColumn::make('ibu_bekerja')
                    ->label('Status Ibu bekerja')
                    ->formatStateUsing(function ($state) {
                        return $state == 1 ? 'Ya' : 'Tidak';
                    }),
                Tables\Columns\TextColumn::make('pendidikan_ibu')->label('Pendidikan Ibu'),
                Tables\Columns\TextColumn::make('orang_tua_merokok')
                    ->label('Status orang tua merokok')
                    ->formatStateUsing(function ($state) {
                        return $state == 1 ? 'Ya' : 'Tidak';
                    }),
                Tables\Columns\TextColumn::make('penyakit_anak_penyerta.penyakit.nama_penyakit')
                    ->label('Penyakit penyerta')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->penyakit_anak_penyerta->pluck('penyakit.nama_penyakit')->implode(', ');
                    }),
                Tables\Columns\TextColumn::make('penyakit_anak_komplikasi.penyakit.nama_penyakit')
                    ->label('Penyakit komplikasi kehamilan Ibu')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->penyakit_anak_komplikasi->pluck('penyakit.nama_penyakit')->implode(', ');
                    })
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                SelectFilter::make('orang_tua_merokok')
                    ->label('Status orang tua merokok')
                    ->options([
                        0 => 'Tidak',
                        1 => 'Ya',
                    ]),
                SelectFilter::make('berat_lahir')
                    ->label('Riwayat kelahiran')
                    ->options([
                        'rendah' => 'Rendah (Kurang dari 2,5 kg)',
                        'normal' => 'Normal (Lebih dari 2,5 kg)',
                    ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnakSakits::route('/'),
            'create' => Pages\CreateAnakSakit::route('/create'),
            'edit' => Pages\EditAnakSakit::route('/{record}/edit'),
        ];
    }
}
