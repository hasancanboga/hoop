<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Challenge
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $min_age
 * @property int $max_age
 * @property int|null $max_participants
 * @property string $reward
 * @property int $stock_image_id
 * @property string $video
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stunt[] $stunts
 * @property-read int|null $stunts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereMaxAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereMaxParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereMinAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereReward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereStockImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge whereVideo($value)
 * @mixin \Eloquent
 */
	class IdeHelperChallenge extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChallengeParticipation
 *
 * @property int $id
 * @property int $user_id
 * @property int $challenge_id
 * @property string $video
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereChallengeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChallengeParticipation whereVideo($value)
 * @mixin \Eloquent
 */
	class IdeHelperChallengeParticipation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Like
 *
 * @mixin IdeHelperLike
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Query\Builder|Like onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Like withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Like withoutTrashed()
 */
	class IdeHelperLike extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Locality
 *
 * @mixin IdeHelperLocality
 * @property int $id
 * @property int $user_id
 * @property string $country_code
 * @property int|null $state_code
 * @property int|null $city_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Locality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Locality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Locality query()
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereStateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locality whereUserId($value)
 */
	class IdeHelperLocality extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @mixin IdeHelperPost
 * @property int $id
 * @property int $user_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostImage[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 */
	class IdeHelperPost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostImage
 *
 * @property int $id
 * @property int $post_id
 * @property string|null $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPostImage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StockImage
 *
 * @property int $id
 * @property string $image
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperStockImage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Stunt
 *
 * @property int $id
 * @property int $challenge_id
 * @property string $description
 * @property int $repetitions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Challenge $challenge
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereChallengeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereRepetitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stunt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperStunt extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @mixin IdeHelperUser
 * @property int $id
 * @property string $phone
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $date_of_birth
 * @property string|null $parent_first_name
 * @property string|null $parent_last_name
 * @property string|null $parent_phone
 * @property string|null $profile_image
 * @property string|null $otp
 * @property string|null $otp_expiry
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $follows
 * @property-read int|null $follows_count
 * @property-read int $age
 * @property-read string $full_name
 * @property-read string|null $parent_full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\Locality|null $locality
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class IdeHelperUser extends \Eloquent {}
}

