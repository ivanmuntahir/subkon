<?php

namespace App\Filament\Resources\SubkonResource\Pages;

use App\Filament\Resources\SubkonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubkon extends EditRecord
{
    protected static string $resource = SubkonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
