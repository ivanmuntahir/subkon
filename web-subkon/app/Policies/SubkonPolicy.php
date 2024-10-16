<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subkon;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubkonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_subkon');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Subkon $subkon): bool
    {
        return $user->can('view_subkon');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_subkon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subkon $subkon): bool
    {
        return $user->can('update_subkon');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subkon $subkon): bool
    {
        return $user->can('delete_subkon');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_subkon');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Subkon $subkon): bool
    {
        return $user->can('force_delete_subkon');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_subkon');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Subkon $subkon): bool
    {
        return $user->can('restore_subkon');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_subkon');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Subkon $subkon): bool
    {
        return $user->can('replicate_subkon');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_subkon');
    }
}
