<?php /** @noinspection PhpUnused */

namespace App\Models;

use App\Http\Controllers\Api\UserController;
use App\Traits\Followable;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'username',
        'profile_image',
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
        'created_at',
        'updated_at',
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

    public function timeline($withUsers = false): LengthAwarePaginator
    {
        $followedIds = $this->follows()->pluck('id');

        $posts = Post::when($withUsers, function ($query) {
            return $query->with('user');
        })
            ->with('images')
            ->whereIn('user_id', $followedIds)
            ->orWhere('user_id', $this->id);

        return $posts->withCount('likes')->latest()->paginate(10);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->withCount('likes')->latest();
    }

    public function getProfileImageAttribute($value): ?string
    {
        return $value ? Storage::url($value) : null;
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

    /**
     * Returns the path to this specific user's show method.
     * Can be used for profile sharing in the future.
     *
     * @param string $append
     * @return string
     */
    public function path(string $append = ''): string
    {
        $path = action([UserController::class, 'show'], $this->username);
        return $append ? $path . '/' . $append : $path;
    }

    public function deleteProfileImage()
    {
        if ($this->profile_image) {
            // getRawOriginal() used in order to skip eloquent accessor (turns into URL)
            Storage::delete($this->getRawOriginal('profile_image'));
            $this->update(['profile_image' => null]);
        }
    }

}
