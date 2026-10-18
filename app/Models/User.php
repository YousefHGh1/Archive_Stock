<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'section_id',
        'sub_section_id',
        'employee_name',
    ];

    public function custody()
    {
        return $this->hasMany(Custody::class, 'user_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id')->latest();
    }

    /**
     * Record an activity entry for this user.
     */
    public function recordActivity($description, array $metadata = [])
    {
        return $this->activities()->create([
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    public function invoiceExport()
    {
        return $this->hasMany(InvoiceExport::class, 'user_id', 'id');
    }

    public function archiveNot()
    {
        return $this->hasMany(archiveNot::class, 'reciver');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'sub_section_id', 'id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Allow admin and super-user roles to bypass permission checks.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if ($this->hasRole(['admin', 'Super-User'])) {
            return true;
        }

        $permissionName = $permission instanceof \Spatie\Permission\Contracts\Permission  ? $permission->name : $permission;
        $permissionName = is_string($permissionName) ? $permissionName : (string) $permissionName;

        $aliases = [
            'users-read'         => ['users-read', 'users'],
            'users-create'       => ['users-create', 'users'],
            'users-update'       => ['users-update', 'users'],
            'users-delete'       => ['users-delete', 'users'],
            'roles-read'         => ['roles-read', 'roles'],
            'roles-create'       => ['roles-create', 'roles'],
            'roles-update'       => ['roles-update', 'roles'],
            'roles-delete'       => ['roles-delete', 'roles'],
            'permissions-read'   => ['permissions-read', 'permissions'],
            'permissions-create' => ['permissions-create', 'permissions'],
            'permissions-update' => ['permissions-update', 'permissions'],
            'permissions-delete' => ['permissions-delete', 'permissions'],
            'items-read'         => ['items-read', 'items'],
            'items-create'       => ['items-create', 'items'],
            'items-update'       => ['items-update', 'items'],
            'items-delete'       => ['items-delete', 'items'],
        ];

        $candidates = [$permissionName];
        if (isset($aliases[$permissionName])) {
            $candidates = array_merge($candidates, $aliases[$permissionName]);
        }

        foreach (array_unique($candidates) as $candidate) {
            try {
                // Replicate \Spatie\Permission\Traits\HasPermissions::hasPermissionTo()
                // since it lives on the trait (via HasRoles), not on the parent class.
                if ($this->getWildcardClass()) {
                    if ($this->hasWildcardPermission($candidate, $guardName)) {
                        return true;
                    }
                } else {
                    $permissionModel = $this->filterPermission($candidate, $guardName);

                    if ($this->hasDirectPermission($permissionModel) || $this->hasPermissionViaRole($permissionModel)) {
                        return true;
                    }
                }
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                // Permission is not registered; try the next candidate/alias.
                continue;
            }
        }

        return false;
    }

    /**
     * Check if the user has any of the given permissions.
     */
    public function hasAnyPermission($permissions): bool
    {
        if ($this->hasRole(['admin', 'Super-User'])) {
            return true;
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        foreach ($permissions as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can access data from a specific section
     */
    public function canAccessSection($sectionId)
    {
        if ($this->hasRole(['admin', 'Super-User']) || $this->hasAnyPermission(['view-all-sections'])) {
            return true;
        }

        return $this->section_id == $sectionId;
    }

    /**
     * Check if user can manage data from a specific section
     */
    public function canManageSection($sectionId)
    {
        if ($this->hasRole(['admin', 'Super-User']) || $this->hasAnyPermission(['manage-all-sections'])) {
            return true;
        }

        if ($this->hasAnyPermission(['manage-own-section'])) {
            return $this->section_id == $sectionId;
        }

        return false;
    }

    /**
     * Check if user can access data from a specific subsection
     */
    public function canAccessSubSection($subSectionId)
    {
        // Super users and admins can access all subsections
        if ($this->hasRole(['admin', 'Super-User']) || $this->can('view-all-sections')) {
            return true;
        }

        // Users can access their own subsection
        return $this->sub_section_id == $subSectionId;
    }

    /**
     * Scope to filter data by user's section access
     */
    public function scopeWithSectionAccess($query, $sectionColumn = 'section_id')
    {
        $user = auth()->user();

        // If user can view all sections, return all data
        if ($user && ($user->hasRole(['admin', 'Super-User']) || $user->hasAnyPermission(['view-all-sections']))) {
            return $query;
        }

        // Otherwise, filter by user's section
        if ($user && $user->section_id) {
            return $query->where($sectionColumn, $user->section_id);
        }

        // If no section assigned, return empty
        return $query->whereRaw('1 = 0');
    }
}
