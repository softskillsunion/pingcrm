<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CustomersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customers/Index', [
            'filters' => Request::all('search', 'trashed'),
            'customers' => Auth::user()->account->customers()
                ->with('user')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn($customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile,
                    'email' => $customer->email,
                    'mailing_address' => $customer->mailing_address,
                    'deleted_at' => $customer->deleted_at,
                    'user' => $customer->user ? $customer->user->only('first_name') : null,
                ]),
            // return response()->json([$requestedUser]);
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Customers/Create', [
            'users' => Auth::user()->account
                ->users()
                ->orderBy('first_name')
                ->get()
                ->map
                ->only('id', 'first_name'),
        ]);
    }

    public function store(): RedirectResponse
    {
        // 檢查請求是否有進入
        // dd("Store method hit!");
        Auth::user()->account->customers()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                // 'last_name' => ['required', 'max:50'],
                'user_id' => ['nullable', Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'mobile' => ['nullable', 'max:50'],
                'mailing_address' => ['nullable', 'max:150'],
                'registered_address' => ['nullable', 'max:150'],
            ])
        );

        return Redirect::route('customers')->with('success', '客戶資料已建立');
    }

    public function edit(Customer $customer): Response
    {
        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                // 'last_name' => $customer->last_name,
                'user_id' => $customer->user_id,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'mobile' => $customer->mobile,
                'mailing_address' => $customer->mailing_address,
                'registered_address' => $customer->registered_address,
                'deleted_at' => $customer->deleted_at,
            ],
            'users' => Auth::user()->account->users()
                ->orderBy('first_name')
                ->get()
                ->map
                ->only('id', 'first_name'),
        ]);
    }

    public function update(Customer $customer): RedirectResponse
    {
        $customer->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'user_id' => [
                    'nullable',
                    Rule::exists('users', 'id')->where(fn($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'mobile' => ['nullable', 'max:150'],
                'mailing_address' => ['nullable', 'max:150'],
                'registered_address' => ['nullable', 'max:150'],
            ])
        );

        return Redirect::back()->with('success', '客戶資料已更新');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return Redirect::back()->with('success', '客戶資料已刪除');
    }

    public function restore(Customer $customer): RedirectResponse
    {
        $customer->restore();

        return Redirect::back()->with('success', '客戶資料已還原');
    }
}
