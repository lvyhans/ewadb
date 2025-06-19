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
        'approved_by'
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
     * Get primary role name (first role or default to 'user')
     */
    public function getRoleAttribute()
    {
        $firstRole = $this->roles()->first();
        return $firstRole ? $firstRole->name : 'user';
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
     * Check if this user is the first registered administrator
     */
    public function isFirstAdmin()
    {
        // Check if this user has admin role and is the first user in the system
        return $this->hasRole('admin') && $this->id === User::orderBy('id')->first()?->id;
    }
    
    /**
     * Check if this user can be edited/deleted from user management
     */
    public function canBeManaged()
    {
        return !$this->isFirstAdmin();
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
}
