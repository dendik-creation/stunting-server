<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(3)
            ->schema([
                Forms\Components\TextInput::make('username')->label('Username')->required(),
                Forms\Components\TextInput::make('nama_lengkap')->label('Nama lengkap')->required(),
                Forms\Components\Select::make('role')
                ->label('Role')
                ->options([
                    'admin' => 'Admin',
                    'operator' => 'Operator',
                    'dinas' => 'Dinas',
                ])
                ->reactive()
                ->default(function(string $context){
                    if(auth()->user()->role == 'operator' && $context === "create"){
                        return 'operator';
                    }
                })
                ->disabled(fn (string $context) => auth()->user()->role == "operator")
                ->dehydrateStateUsing(function(string $context, string $state){
                    if(auth()->user()->role == 'operator'){
                        return 'operator';
                    }else{
                        return $state;
                    }
                })
                ->dehydrated(function(string $context, string $state){
                    if(auth()->user()->role == 'operator'){
                        return 'operator';
                    }else{
                        return $state;
                    }

                })
                ->required(),
                Forms\Components\TextInput::make('password')
                ->label(fn (string $context): string => $context === 'create' ? 'Password' : 'Password (abaikan jika tidak ingin merubah)')
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->password()
                ->revealable()
                ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\Select::make('puskesmas_id')
                ->relationship('puskesmas', 'nama_puskesmas')
                ->label('Puskesmas')
                ->required()
                ->disabled(fn (string $context) => auth()->user()->role == "operator")
                ->default(function(string $context){
                    if(auth()->user()->role == 'operator' && $context === "create"){
                        return auth()->user()->puskesmas_id;
                    }
                })
                ->dehydrateStateUsing(fn ($state) => auth()->user()->puskesmas_id)
                ->dehydrated(fn ($state) => filled(auth()->user()->puskesmas_id))
                ->hidden(function ($get) {
                    return $get('role') != 'operator' || $get('role') == '';
                })
                ->searchable()
                ->preload(),
                Forms\Components\Select::make('kabupaten_id')
                ->relationship('kabupaten', 'nama_kabupaten')
                ->label('Kabupaten')
                ->searchable()
                ->required()
                ->hidden(function ($get) {
                    return auth()->user()->role == "operator" || $get('role') != 'dinas' || $get('role') == '';
                })
                ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(auth()->user()->role == 'operator' ? User::where('puskesmas_id', auth()->user()->puskesmas_id) : User::query())
            ->columns([
                Tables\Columns\TextColumn::make('username')->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')->searchable(),
                Tables\Columns\TextColumn::make('role')->searchable()->formatStateUsing(function($state){
                    return ucfirst($state);
                }),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')->label('Puskesmas')->formatStateUsing(function($state, $record){
                    return $record->role == 'operator' ? $state : "";
                }),
                Tables\Columns\TextColumn::make('kabupaten.nama_kabupaten')->label('Kabupaten')->formatStateUsing(function($state, $record){
                    return $record->role == 'dinas' ? $state : "";
                })->hidden(auth()->user()->role == 'operator'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reset_password')
                ->label('Reset Password')
                ->color('danger')
                ->icon('heroicon-o-key')
                ->action(function (User $record) {
                    $record->update([
                        'password' => Hash::make('12345'),
                    ]);
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
