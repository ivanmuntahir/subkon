<?php

namespace App\Enums;

use Filament\Support\Enum;
use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum EmployeeStatus: string
{
    use IsKanbanStatus;

    case Available = 'available';
    case Assigned = 'assigned';

    public static function kanbanCases(): array
    {
        return [
            static::Available,
            static::Assigned,
        ];
    }

    public function getTitle(): string
    {
        return $this->name;
    }
   
}

