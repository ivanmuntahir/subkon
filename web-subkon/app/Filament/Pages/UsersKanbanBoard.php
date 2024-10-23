<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\ProjectAssignment;
use App\Enums\ProjectStatus;

class UsersKanbanBoard extends KanbanBoard
{
    protected static string $model = User::class;
    protected static string $statusEnum = ProjectStatus::class;

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
        return ProjectStatus::statuses();
    }

    protected function records(): Collection
    {
        return User::ordered()->get();
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        User::find($recordId)->update(['status' => $status]);
        User::setNewOrder($toOrderedIds);
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
        User::setNewOrder($orderedIds);
    }

}
