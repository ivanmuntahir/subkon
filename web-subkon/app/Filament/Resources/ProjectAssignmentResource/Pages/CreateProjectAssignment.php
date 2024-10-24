<?php

namespace App\Filament\Resources\ProjectAssignmentResource\Pages;

use App\Filament\Resources\ProjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Model\Employee;

class CreateProjectAssignment extends CreateRecord
{
    protected static string $resource = ProjectAssignmentResource::class;

}
