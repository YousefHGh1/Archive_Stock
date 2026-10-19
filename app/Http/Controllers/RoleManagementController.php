<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-update', ['only' => ['edit', 'update', 'assignPermissions']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of all roles with their permissions
     */
    public function index()
    {
        $roles              = Role::with('permissions')->orderBy('id')->paginate(10);
        $permissions        = Permission::orderBy('name')->paginate(15);
        $groupedPermissions = $this->getGroupedPermissions();

        return view('roles.index', compact('roles', 'permissions', 'groupedPermissions'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions        = Permission::orderBy('name')->paginate(15);
        $groupedPermissions = $this->getGroupedPermissions();

        return view('roles.create', compact('permissions', 'groupedPermissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|unique:roles,name',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح!');
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        $role               = Role::with('permissions')->findOrFail($id);
        $permissions        = Permission::orderBy('name')->paginate(15);
        $groupedPermissions = $this->getGroupedPermissions();

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|unique:roles,name,' . $id,
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role       = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'تم تحديث الدور بنجاح!');
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of critical roles
        if (in_array($role->name, ['admin', 'Super-User'])) {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف الأدوار الأساسية!');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح!');
    }

    /**
     * Assign permissions to a role via AJAX
     */
    public function assignPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصلاحيات بنجاح!',
        ]);
    }

    private function getGroupedPermissions()
    {
        $permissions = Permission::orderBy('name')->get();

        return $permissions->groupBy(function ($permission) {
            $baseName = str_contains($permission->name, '-')
                ? explode('-', $permission->name)[0]
                : $permission->name;

            $labels = [
                'users'       => 'المستخدمين',
                'roles'       => 'الأدوار',
                'permissions' => 'الصلاحيات',
                'items'       => 'الأصناف',
                'categories'  => 'التصنيفات',
                'units'       => 'الوحدات',
                'suppliers'   => 'الموردين',
                'supplier'    => 'الموردين',
                'invoices'    => 'الفواتير',
                'invoice'     => 'الفواتير',
                'purchase'    => 'المشتريات',
                'stock'       => 'المخزون',
                'archive'     => 'الأرشيف',
                'report'      => 'التقارير',
                'section'     => 'الأقسام',
                'sections'    => 'الأقسام',
                'currency'    => 'العملات',
                'employee'    => 'الموظفين',
                'custody'     => 'العهد',
                'diesel'      => 'المحروقات',
                'vehicles' => 'المركبات',
                'vehicle' => 'المركبات',
                'drivers' => 'السائقون',
                'driver' => 'السائقون',
                'legal'       => 'الشؤون القانونية',
                'computer'    => 'الحاسوب',
                'censorship'  => 'الرقابة',
                'jibaya'      => 'الجباية',
            ];

            return $labels[strtolower($baseName)] ?? ucfirst($baseName);
        });
    }
}
