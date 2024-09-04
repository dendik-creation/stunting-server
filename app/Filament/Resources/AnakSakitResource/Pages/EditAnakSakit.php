<?php

namespace App\Filament\Resources\AnakSakitResource\Pages;

use App\Filament\Resources\AnakSakitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnakSakit extends EditRecord
{
    protected static string $resource = AnakSakitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
