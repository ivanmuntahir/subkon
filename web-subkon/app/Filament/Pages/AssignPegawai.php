<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use App\Enums\EmployeeStatus;
use Illuminate\Support\Collection;

class AssignPegawai extends KanbanBoard
{
    protected static string $model = Employee::class;
    protected static string $statusEnum = EmployeeStatus::class;

    protected static string $recordTitleAttribute = 'name';

    protected function statuses(): Collection
    {
        //  return collect([
        //     [
        //         'id' => 'pending',
        //         'title' => 'Pending',
        //     ],
        //     [
        //         'id' => 'active',
        //         'title' => 'Active',
        //     ]
        //     ]);
        return EmployeeStatus::statuses();
    }

    protected function records(): Collection
    {
        return Employee::ordered()->get();
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        Employee::find($recordId)->update(['status' => $status]);
        // Employee::setNewOrder($toOrderedIds);
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
        // Employee::setNewOrder($orderedIds);
    }
}
