<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\Users\User;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('admin.users.index', compact('roles'));
    }

    public function data(): JsonResponse
    {
        $roles = Role::query()->orderBy('name')->pluck('name');

        $query = User::query()->with('roles')->latest();

        return DataTables::eloquent($query)
            ->addColumn('role', fn (User $user) => $user->roles->first()?->name ?? '—')
            ->addColumn('role_form', function (User $user) use ($roles) {
                $currentRole = $user->roles->first()?->name ?? '';
                $options = '';

                foreach ($roles as $role) {
                    $selected = $currentRole === $role ? 'selected' : '';
                    $escapedRole = e($role);
                    $options .= "<option value=\"{$escapedRole}\" {$selected}>{$escapedRole}</option>";
                }

                return view('admin.users.partials.role-form', [
                    'user' => $user,
                    'options' => $options,
                ])->render();
            })
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('Y-m-d H:i:s') ?? '—')
            ->rawColumns(['role_form'])
            ->toJson();
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        UserService::updateRole($user, $request->validated('role'));

        return redirect()->route('admin.users.index')->with('status', 'User role updated.');
    }
}
