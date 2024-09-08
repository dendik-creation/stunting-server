<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeluargaResource\Pages;
use App\Filament\Resources\KeluargaResource\RelationManagers;
use App\Models\Keluarga;
use App\Models\Puskesmas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class KeluargaResource extends Resource
{
    protected static ?string $model = Keluarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form->schema([Forms\Components\TextInput::make('nik')->label('NIK')->required(), Forms\Components\TextInput::make('nama_lengkap')->required(), Forms\Components\TextInput::make('desa')->required(), Forms\Components\TextInput::make('rt')->required(), Forms\Components\TextInput::make('rw')->required(), Forms\Components\Textarea::make('alamat')->required()->label('Alamat Lengkap'), Forms\Components\Select::make('puskesmas_id')->relationship('puskesmas', 'nama_puskesmas')->required()->label('Puskesmas')]);
    }

    public static function getKeluargaByRole(): Builder
    {
        return (new static::$model())->newQuery()
            ->when(auth()->user()->role == 'operator', function ($query) {
                return $query->where('puskesmas_id', auth()->user()->puskesmas_id);
            })
            ->when(auth()->user()->role == 'dinas', function ($query) {
                return $query->whereHas('puskesmas', function ($query) {
                    $query->where('kabupaten_id', auth()->user()->kabupaten_id);
                });
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getKeluargaByRole())
            ->columns([
                Tables\Columns\TextColumn::make('nik')->label('NIK')->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')->searchable(),
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('rt'),
                Tables\Columns\TextColumn::make('rw'),
                Tables\Columns\TextColumn::make('no_telp'),
                Tables\Columns\TextColumn::make('is_free_stunting')
                    ->label('Status Tes')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->is_test_done) {
                            return 'Tes berjalan';
                        } elseif ($record->is_test_done && $record->is_free_stunting) {
                            return 'Tes berhasil';
                        } elseif ($record->is_test_done && !$record->is_free_stunting) {
                            return 'Tes gagal';
                        }
                    }),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')->searchable(),
            ])
            ->filters([
                SelectFilter::make('puskesmas_id')->label('Puskesmas')->options(function(){
                    $puskemsas = Puskesmas::query();
                    return auth()->user()->role == 'dinas' ? $puskemsas->where('kabupaten_id', auth()->user()->kabupaten_id)->get()->pluck('nama_puskesmas', 'id') : $puskemsas->get()->pluck('nama_puskesmas', 'id');
                })->hidden(auth()->user()->role == 'operator')->searchable(),
                SelectFilter::make('is_free_stunting')->label('Hasil Akhir')->options([
                    0 => 'Gagal',
                    1 => 'Berhasil',
                ]),
                SelectFilter::make('is_test_done')->label('Kondisi Tes')->options([
                    0 => 'Berjalan',
                    1 => 'Selesai',
                ]),
                DateRangeFilter::make('created_at')
                    ->placeholder('Dari - Sampai')
                    ->label('Tanggal registrasi keluarga'),
            ]) ->headerActions([
                Tables\Actions\Action::make('exportBulkKeluarga')
                    ->label('Cetak Data')
                    ->color('success')
                    ->icon('heroicon-s-printer')
                    ->disabled(fn (Table $table) => $table->getRecords()->count() == 0)
                    ->action(function (Table $table) {
                        $filters = $table->getFiltersForm()->getState();
                        return redirect()->route('keluarga.export.bulk', $filters);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\Action::make('exportSingleKeluarga')
                ->label('Cetak')
                ->icon('heroicon-s-printer')
                ->color('success')
                ->disabled( fn ($record) => !$record->is_test_done)
                ->action(function ($record) {
                    return redirect()->to('/keluarga/export/single/' . $record->id);
                })->openUrlInNewTab()
                ])
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
            'index' => Pages\ListKeluargas::route('/'),
            'create' => Pages\CreateKeluarga::route('/create'),
            'edit' => Pages\EditKeluarga::route('/{record}/edit'),
        ];
    }
}

