<?php

namespace App\Models;

use App\Traits\Followable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'gender',
        'birth_year',
        'email',
        'locality_id',
        'otp',
        'otp_expiry',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'otp',
        'otp_expiry',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'avatar'
    ];

    public function username()
    {
        return $this->phone;
    }

    public function getAuthPassword()
    {
        return $this->otp;
    }

    public function locality()
    {
        return $this->hasOne(Locality::class);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst($value);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function hasCompletedRegistration()
    {
        return $this->first_name && $this->last_name && $this->gender && $this->birth_year;
    }

    public function timeline($withUsers = false)
    {
        $followedIds = $this->follows()->pluck('id');

        $posts = Post::when($withUsers, function ($query) {
            return $query->with('user');
        })
            ->whereIn('user_id', $followedIds)
            ->orWhere('user_id', $this->id);

        return $posts->latest()->get();
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function getAvatarAttribute()
    {
        return "https://i.pravatar.cc/120?u=" . $this->phone;
    }
}
