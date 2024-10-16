<?php

namespace App\Filament\Resources\SubkonResource\Pages;

use App\Filament\Resources\SubkonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubkons extends ListRecords
{
    protected static string $resource = SubkonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
