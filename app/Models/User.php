<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'is_active', 'must_change_password', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')->withTimestamps();
    }

    public function roleSlugs(): array
    {
        return $this->roles()->pluck('slug')->all();
    }

    public function hasRole(UserRole|string $role): bool
    {
        $slug = $role instanceof UserRole ? $role->value : $role;

        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        $slugs = array_map(
            fn (UserRole|string $role) => $role instanceof UserRole ? $role->value : $role,
            $roles,
        );

        return $this->roles()->whereIn('slug', $slugs)->exists();
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(UserRole::Customer);
    }

    public function assignRole(UserRole|string $role): void
    {
        $role = Role::bySlug($role instanceof UserRole ? $role : UserRole::from($role));

        if ($role !== null && ! $this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role);
        }
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
