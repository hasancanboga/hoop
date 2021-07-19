<?php /** @noinspection PhpUnused */

namespace App\Models;

use App\Traits\Followable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
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
        'date_of_birth',
        'email',
        'locality_id',
        'otp',
        'otp_expiry',
        'parent_phone',
        'parent_first_name',
        'parent_last_name',
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
        'parent_full_name',
        // 'avatar',
    ];

    public function username(): string
    {
        return $this->phone;
    }

    public function getAuthPassword(): ?string
    {
        return $this->otp;
    }

    public function locality(): HasOne
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

    public function getFullNameAttribute(): string
    {
        return "$this->first_name $this->last_name";
    }

    public function getParentFullNameAttribute(): ?string
    {
        if ($this->parent_first_name && $this->parent_last_name) {
            return $this->parent_first_name . " " . $this->parent_last_name;
        }
        return null;
    }

    public function hasCompletedRegistration(): bool
    {
        return $this->first_name && $this->last_name && $this->gender && $this->date_of_birth && $this->username;
    }

    public function hasRegisteredParent(): bool
    {
        return $this->parent_first_name && $this->parent_last_name && $this->parent_phone;
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_of_birth)->age;
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function getAvatarAttribute(): string
    {
        return "https://i.pravatar.cc/120?u=" . $this->phone;
    }

    public function generateUniqueUsername()
    {
        $username = strtolower(preg_replace(
            "/[^A-Za-z]/",
            '',
            $this->full_name
        ));

        if (strlen($username) < 3) {
            $username = strtolower(config('app.name') . 'user') . rand(111111, 999999);
        }

        $i = 0;
        while (static::whereUsername($username)->exists()) {
            $username = $username . ++$i;
        }
        $this->username = $username;
        $this->save();
    }
}
