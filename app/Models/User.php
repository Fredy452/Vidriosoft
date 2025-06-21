<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city',
        'address',
        'is_admin',
        'is_active',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'is_admin' => false,
        'is_active' => true,
        'password' => '12345678', // Default password, should be changed on first login
    ];

    /**
     * Bootstrap the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Solo hashear si el password no ha sido modificado manualmente
            if ($user->password === '12345678') {
                $user->password = Hash::make('12345678');
            }
        });
    }

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
        ];
    }

    /**
     * Get the filament access panel
     * @return string
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if(env('APP_ENV') === 'production') {
            if ($panel->getId() === 'admin') {
                return str_ends_with($this->email, '@test.com');
            }
        }

        return true;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
}
