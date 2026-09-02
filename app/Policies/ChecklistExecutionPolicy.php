<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ChecklistExecution;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChecklistExecutionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChecklistExecution');
    }

    public function view(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('View:ChecklistExecution');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChecklistExecution');
    }

    public function update(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('Update:ChecklistExecution');
    }

    public function delete(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('Delete:ChecklistExecution');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ChecklistExecution');
    }

    public function restore(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('Restore:ChecklistExecution');
    }

    public function forceDelete(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('ForceDelete:ChecklistExecution');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChecklistExecution');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChecklistExecution');
    }

    public function replicate(AuthUser $authUser, ChecklistExecution $checklistExecution): bool
    {
        return $authUser->can('Replicate:ChecklistExecution');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChecklistExecution');
    }
}
