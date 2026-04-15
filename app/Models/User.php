<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        'bio',
        'avatar',
        'role',
        'status',
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
        ];
    }

    /**
     * User's properties (as owner/admin)
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * User's favorite properties
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * User's inquiries
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * User's reservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * User's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
