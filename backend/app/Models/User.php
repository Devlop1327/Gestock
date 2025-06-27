<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**     
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'activo',
        'is_admin',
        'ultimo_acceso',
        'intentos_login',
        'bloqueado_hasta',
        'theme',
        'notifications_enabled'
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
        'is_admin' => 'boolean',
        'ultimo_acceso' => 'datetime',
        'bloqueado_hasta' => 'datetime'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function tienePermiso(string $permiso): bool
    {
        return $this->role && $this->role->tienePermiso($permiso);
    }

    public function esAdmin(): bool
    {
        return $this->role && in_array($this->role->nombre, ['admin', 'Administrador']);
    }

    public function esAdministrador()
    {
        return $this->role && $this->role->nombre === 'Administrador';
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->nombre, $roles);
    }

    public function incrementarIntentosLogin()
    {
        $this->intentos_login += 1;
        if ($this->intentos_login >= 3) {
            $this->bloqueado_hasta = now()->addMinutes(30);
        }
        $this->save();
    }

    public function resetearIntentosLogin()
    {
        $this->intentos_login = 0;
        $this->bloqueado_hasta = null;
        $this->save();
    }
}
