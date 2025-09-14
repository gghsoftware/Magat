<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return view('admin.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
=======
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q'));
        $status = $request->query('status');
        $sort   = $request->query('sort', 'latest');

        $customers = User::query()
            ->onlyCustomers()
            ->with('role')
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->search($q)
            ->when($sort === 'name',   fn ($qq) => $qq->orderBy('name'))
            ->when($sort === 'oldest', fn ($qq) => $qq->orderBy('id', 'asc'))
            ->when($sort === 'latest', fn ($qq) => $qq->orderBy('id', 'desc'))
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'q', 'status', 'sort'));
    }

    public function create()
    {
        $customer = new User;
        return view('admin.customers.create', compact('customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_id'  => ['nullable', 'integer', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'address'  => ['nullable', 'string'],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Default role to "customer" if not provided but exists
        if (empty($data['role_id'])) {
            $data['role_id'] = Role::query()->where('role_name', 'customer')->value('id');
        }

        // Password hashing handled by your User mutator
        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created.');
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $data = $request->validate([
            'role_id'  => ['nullable', 'integer', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users','email')->ignore($customer->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'address'  => ['nullable', 'string'],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Keep existing password if left blank
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(User $customer)
    {
        // Optional guards
        if ($customer->role && $customer->role->role_name !== 'customer') {
            return back()->with('success', 'Only customers can be deleted.');
        }
        if (auth()->id() === $customer->id) {
            return back()->with('success', "You can't delete your own account.");
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted.');
>>>>>>> 54d403e (Initial commit of Magat Funeral project)
    }
}
