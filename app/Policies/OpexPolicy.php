<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Opex;
use Illuminate\Auth\Access\HandlesAuthorization;

class OpexPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Opex');
    }

    public function view(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('View:Opex');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Opex');
    }

    public function update(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('Update:Opex');
    }

    public function delete(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('Delete:Opex');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Opex');
    }

    public function restore(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('Restore:Opex');
    }

    public function forceDelete(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('ForceDelete:Opex');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Opex');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Opex');
    }

    public function replicate(AuthUser $authUser, Opex $opex): bool
    {
        return $authUser->can('Replicate:Opex');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Opex');
    }

}