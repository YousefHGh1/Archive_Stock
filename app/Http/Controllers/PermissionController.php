<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:permissions-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permissions-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permissions-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of all permissions
     */
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(15);
        return view('vendor.PermissionsUI.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        return view('vendor.PermissionsUI.permissions.create');
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('permission_ui.permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح!');
    }

    /**
     * Display the specified permission
     */
    public function show($id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        return view('vendor.PermissionsUI.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('vendor.PermissionsUI.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permission_ui.permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح!');
    }

    /**
     * Remove the specified permission
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permission_ui.permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح!');
    }
}
