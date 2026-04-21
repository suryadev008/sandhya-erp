<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $roles = Auth::check() && Auth::user()->hasRole('admin')
            ? Role::orderBy('name')->pluck('name')
            : collect();

        return view('auth.register', compact('roles'));
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Only trust role input from authenticated admins
        $isAdmin = Auth::check() && Auth::user()->hasRole('admin');
        $roleName = $isAdmin && $request->filled('role') && Role::where('name', $request->role)->exists()
            ? $request->role
            : 'viewer';

        $user->assignRole($roleName);

        event(new Registered($user));

        // Admin stays logged in; guest logs in as new user
        if (! $isAdmin) {
            Auth::login($user);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
