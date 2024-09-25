<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuskesmasResource\Pages;
use App\Filament\Resources\PuskesmasResource\RelationManagers;
use App\Models\Kabupaten;
use App\Models\Puskesmas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuskesmasResource extends Resource
{
    protected static ?string $model = Puskesmas::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-s-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_puskesmas')->label('Nama Puskesmas')->required(),
                Forms\Components\Textarea::make('alamat')->required()->label('Alamat Lengkap'),
                Forms\Components\Select::make('kabupaten_id')->required()->label('Kabupaten')->relationship('kabupaten', 'nama_kabupaten')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_puskesmas')->searchable(),
                Tables\Columns\TextColumn::make('alamat')->searchable(),
                Tables\Columns\TextColumn::make('kabupaten.nama_kabupaten')->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kabupaten_id')->label('Kabupaten')->options(fn() => Kabupaten::all()->pluck('nama_kabupaten', 'id'))->searchable()->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPuskesmas::route('/'),
            'create' => Pages\CreatePuskesmas::route('/create'),
            'edit' => Pages\EditPuskesmas::route('/{record}/edit'),
        ];
    }
}
