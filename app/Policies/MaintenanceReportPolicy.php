<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaintenanceReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenanceReportPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaintenanceReport');
    }

    public function view(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('View:MaintenanceReport');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaintenanceReport');
    }

    public function update(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('Update:MaintenanceReport');
    }

    public function delete(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('Delete:MaintenanceReport');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MaintenanceReport');
    }

    public function restore(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('Restore:MaintenanceReport');
    }

    public function forceDelete(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('ForceDelete:MaintenanceReport');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaintenanceReport');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaintenanceReport');
    }

    public function replicate(AuthUser $authUser, MaintenanceReport $maintenanceReport): bool
    {
        return $authUser->can('Replicate:MaintenanceReport');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaintenanceReport');
    }

}