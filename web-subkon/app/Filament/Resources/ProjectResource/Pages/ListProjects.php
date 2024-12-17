<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Proyek Baru'),
            Action::make('assign_employees')
                    ->url(route('assignment'))
                    ->label('Delegasikan Pegawai')
                    ->color('success'),
            Action::make('presensi')
                    ->url(url('/presensi')) 
                    ->label('Presensi')
                    ->color('primary')
        ];
    }
     public function getTitle(): string
    {
        return 'List Proyek'; // Custom title for create page
    }
}
