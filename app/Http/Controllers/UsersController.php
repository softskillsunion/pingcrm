<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                    'commission_rate' => $user->commission_rate,
                    'owner' => $user->owner,
                    'is_active' => $user->is_active,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            // 'last_name' => ['required', 'max:50'],
            'commission_rate' => ['required', 'numeric'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            // 'last_name' => Request::get('last_name'),
            'commission_rate' => Request::get('commission_rate'),
            'phone' => Request::get('phone'),
            'mobile' => Request::get('mobile'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'mailing_address' => Request::get('mailing_address'),
            'registered_address' => Request::get('registered_address'),
            'owner' => Request::get('owner'),
            'is_active' => Request::get('is_active'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('users')->with('success', '成員資料已建立');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                // 'last_name' => $user->last_name,
                'commission_rate' => $user->commission_rate,
                'phone' => $user->phone,
                'mobile' => $user->mobile,
                'email' => $user->email,
                'mailing_address' => $user->mailing_address,
                'registered_address' => $user->registered_address,
                'owner' => $user->owner,
                'is_active' => $user->is_active,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user): RedirectResponse
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            // 'last_name' => ['required', 'max:50'],
            'commission_rate' => ['required', 'numeric'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        // $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));
        $user->update(Request::only('first_name', 'commission_rate', 'phone', 'mobile', 'email', 'mailing_address', 'registered_address', 'owner', 'is_active'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', '成員資料已更新');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', '成員資料已刪除');
    }

    public function restore(User $user): RedirectResponse
    {
        $user->restore();

        return Redirect::back()->with('success', '成員資料已還原');
    }
}
