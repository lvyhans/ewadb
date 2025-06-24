<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_name',
        'company_registration_number',
        'gstin',
        'pan_number',
        'company_address',
        'company_city',
        'company_state',
        'company_pincode',
        'company_phone',
        'company_email',
        'company_registration_certificate',
        'gst_certificate',
        'pan_card',
        'address_proof',
        'bank_statement',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'admin_id',
        'admin_group_name',
        'phone',
        'city',
        'state',
        'zip'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }
    
    /**
     * Roles relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    
    /**
     * Get roles attribute for backward compatibility
     */
    public function getRolesAttribute()
    {
        return $this->roles()->get();
    }
    
    /**
     * Get role names as array
     */
    public function getRoleNamesAttribute()
    {
        return $this->roles()->pluck('name')->toArray();
    }
    
    /**
     * Get primary role name (first role or default to 'admin')
     */
    public function getRoleAttribute()
    {
        $firstRole = $this->roles()->first();
        return $firstRole ? $firstRole->name : 'member';
    }
    
    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
    
    /**
     * Assign a role to the user
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        
        if ($role && !$this->hasRole($role->name)) {
            $this->roles()->attach($role);
        }
        
        return $this;
    }
    
    /**
     * Remove a role from the user
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        
        if ($role) {
            $this->roles()->detach($role);
        }
        
        return $this;
    }
    
    /**
     * Sync user roles
     */
    public function syncRoles($roles)
    {
        $roleIds = [];
        
        foreach ($roles as $role) {
            if (is_string($role)) {
                $roleModel = Role::where('name', $role)->first();
                if ($roleModel) {
                    $roleIds[] = $roleModel->id;
                }
            } elseif (is_numeric($role)) {
                $roleIds[] = $role;
            }
        }
        
        $this->roles()->sync($roleIds);
        return $this;
    }
    
    /**
     * Check if this user is the super admin (first registered admin)
     */
    public function isSuperAdmin()
    {
        // Get the first admin by registration date (earliest created_at)
        $firstAdmin = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->orderBy('created_at')->first();
        
        return $this->hasRole('admin') && $firstAdmin && $this->id === $firstAdmin->id;
    }
    
    /**
     * Check if this user is a regular admin (not super admin)
     */
    public function isRegularAdmin()
    {
        return $this->hasRole('admin') && !$this->isSuperAdmin();
    }
    
    /**
     * Check if this user can be edited/deleted from user management
     */
    public function canBeManaged()
    {
        return !$this->isSuperAdmin();
    }
    
    /**
     * Check if this user should be visible in dashboard
     */
    public function isVisibleInDashboard()
    {
        return !$this->isSuperAdmin();
    }
    
    /**
     * Get the user who approved this user
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    /**
     * Get users approved by this user
     */
    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }
    
    /**
     * Get the admin that this user belongs to
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    /**
     * Get members under this admin
     */
    public function members()
    {
        return $this->hasMany(User::class, 'admin_id');
    }
    
    /**
     * Get all members in the same admin group (including self if admin)
     */
    public function adminGroup()
    {
        if ($this->hasRole('admin')) {
            // If this user is an admin, return their members plus themselves
            return User::where('admin_id', $this->id)->orWhere('id', $this->id);
        } else {
            // If this user is a member, return all members in the same group plus the admin
            return User::where('admin_id', $this->admin_id)->orWhere('id', $this->admin_id);
        }
    }
    
    /**
     * Check if user is approved
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }
    
    /**
     * Check if user is pending approval
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }
    
    /**
     * Check if user is rejected
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }
    
    /**
     * Scope for approved users
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }
    
    /**
     * Scope for pending users
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }
    
    /**
     * Scope for rejected users
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }
    
    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
    
    /**
     * Check if user is a member (has member role)
     */
    public function isMember()
    {
        return $this->hasRole('member');
    }
    
    /**
     * Assign user to an admin group
     */
    public function assignToAdmin($adminId, $groupName = null)
    {
        $admin = User::find($adminId);
        if ($admin && $admin->hasRole('admin')) {
            $this->update([
                'admin_id' => $adminId,
                'admin_group_name' => $groupName ?: $admin->name . "'s Group"
            ]);
        }
        return $this;
    }
    
    /**
     * Scope to get users accessible by a specific admin
     */
    public function scopeAccessibleByAdmin($query, $adminId)
    {
        return $query->where(function($q) use ($adminId) {
            $q->where('admin_id', $adminId)  // Members of this admin
              ->orWhere('id', $adminId);     // Include the admin themselves
        });
    }
    
    /**
     * Scope to get only orphaned users (no admin assigned)
     */
    public function scopeOrphaned($query)
    {
        return $query->whereNull('admin_id')->whereDoesntHave('roles', function($q) {
            $q->where('name', 'admin');
        });
    }
    
    /**
     * Get the admin's members count
     */
    public function getMembersCountAttribute()
    {
        if ($this->isAdmin()) {
            return $this->members()->count();
        }
        return 0;
    }
}
