<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Reply
 * @package App\Models
 *
 * @property int id
 * @property string message
 * @property int topic_id
 * @property int parent_id
 * @property int user_id
 * @property Topic topic
 * @property User user
 * @property Reply child
 */
class Reply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * A reply belongs to a topic.
     *
     * @return BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * A reply belongs to a user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A reply can have many children.
     *
     * @return HasMany
     */
    public function child(): HasMany
    {
        return $this->hasMany(Reply::class, 'parent_id')->orderByDesc('created_at');
    }

    /**
     * Scope function to get the recent replies.
     *
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query): mixed
    {
        return $query->orderBy('created_at', 'desc');
    }
}
