<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\Roles\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->withCount('permissions')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::query()->orderBy('name')->pluck('name');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        RoleService::create($request->validated());

        return redirect()->route('admin.roles.index')->with('status', 'Role created.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()->orderBy('name')->pluck('name');
        $rolePermissions = $role->permissions->pluck('name')->all();
        $isSystemRole = RoleService::isSystemRole($role);

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'isSystemRole'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        RoleService::update($role, $request->validated());

        return redirect()->route('admin.roles.index')->with('status', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        RoleService::delete($role);

        return redirect()->route('admin.roles.index')->with('status', 'Role deleted.');
    }
}
