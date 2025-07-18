<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create() {
        return view('permissions.create');
    }

    public function store(Request $request) {
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index');
    }

    public function edit(Permission $permission) {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission) {
        $permission->update(['name' => $request->name]);
        return redirect()->route('permissions.index');
    }

    public function destroy(Permission $permission) {
        $permission->delete();
        return redirect()->route('permissions.index');
    }
}
