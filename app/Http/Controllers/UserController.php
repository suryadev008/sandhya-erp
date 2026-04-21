<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->pluck('name');
        return view('users.index', compact('roles'));
    }

    public function getData()
    {
        $users = User::with('roles')->orderBy('name')->get()->map(function ($user, $i) {
            $roleName  = $user->roles->first()->name ?? '';
            $roleColor = match ($roleName) {
                'admin'    => 'danger',
                'manager'  => 'warning',
                'operator' => 'info',
                default    => 'secondary',
            };

            $isSelf   = $user->id === auth()->id();
            $editBtn  = '<button class="btn btn-warning btn-xs mr-1 edit-btn"'
                      . ' data-id="' . $user->id . '"'
                      . ' data-name="' . e($user->name) . '"'
                      . ' data-email="' . e($user->email) . '"'
                      . ' data-role="' . e($roleName) . '">'
                      . '<i class="fas fa-edit"></i></button>';
            $delBtn   = $isSelf ? '' :
                        '<button class="btn btn-danger btn-xs delete-btn" data-id="' . $user->id . '">'
                      . '<i class="fas fa-trash"></i></button>';

            $youBadge = $isSelf ? ' <span class="badge badge-info">You</span>' : '';

            return [
                'DT_RowIndex' => $i + 1,
                'name'        => '<i class="fas fa-user-circle text-secondary mr-1"></i>' . e($user->name) . $youBadge,
                'email'       => e($user->email),
                'role'        => $roleName
                    ? '<span class="badge badge-' . $roleColor . '">' . ucfirst($roleName) . '</span>'
                    : '<span class="badge badge-light text-muted">No Role</span>',
                'created_at'  => $user->created_at->format('d M Y'),
                'action'      => $editBtn . $delBtn,
            ];
        });

        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => "User '{$user->name}' created successfully."]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'     => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => "User '{$user->name}' updated successfully."]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 422);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
