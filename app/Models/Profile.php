<?php

namespace App\Models;

use App\Traits\HasPublicUuid;
use Carbon\Carbon;
use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $public_id
 * @property string $first_name
 * @property string $last_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read User $user
 */
final class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    use HasPublicUuid;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
