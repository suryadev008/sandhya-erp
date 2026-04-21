<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($p) => explode(' ', $p->name)[1] ?? 'other');
        return view('roles.index', compact('permissions'));
    }

    public function getData()
    {
        $roles = Role::with('permissions')->orderBy('name')->get()->map(function ($role, $i) {
            $badges = $role->permissions->map(fn ($p) => '<span class="badge badge-secondary mr-1 mb-1">' . e($p->name) . '</span>')->implode('');
            $badges = $badges ?: '<span class="text-muted">No permissions</span>';

            $editBtn = '<button class="btn btn-warning btn-xs mr-1 edit-btn"
                data-id="' . $role->id . '"
                data-name="' . e($role->name) . '"
                data-permissions=\'' . $role->permissions->pluck('name')->toJson() . '\'>
                <i class="fas fa-edit"></i></button>';

            $delBtn = $role->name !== 'admin'
                ? '<button class="btn btn-danger btn-xs delete-btn" data-id="' . $role->id . '"><i class="fas fa-trash"></i></button>'
                : '';

            return [
                'DT_RowIndex' => $i + 1,
                'name'        => '<span class="badge badge-primary" style="font-size:13px">' . e($role->name) . '</span>',
                'permissions' => $badges,
                'action'      => $editBtn . $delBtn,
            ];
        });

        return response()->json(['data' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true, 'message' => "Role '{$role->name}' created successfully."]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100', 'unique:roles,name,' . $role->id],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true, 'message' => "Role '{$role->name}' updated successfully."]);
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return response()->json(['success' => false, 'message' => "Cannot delete the 'admin' role."], 422);
        }

        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
    }
}
