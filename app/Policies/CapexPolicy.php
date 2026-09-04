<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Capex;
use Illuminate\Auth\Access\HandlesAuthorization;

class CapexPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Capex');
    }

    public function view(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('View:Capex');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Capex');
    }

    public function update(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('Update:Capex');
    }

    public function delete(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('Delete:Capex');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Capex');
    }

    public function restore(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('Restore:Capex');
    }

    public function forceDelete(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('ForceDelete:Capex');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Capex');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Capex');
    }

    public function replicate(AuthUser $authUser, Capex $capex): bool
    {
        return $authUser->can('Replicate:Capex');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Capex');
    }

}