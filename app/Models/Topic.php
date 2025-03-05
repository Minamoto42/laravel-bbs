<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Topic
 * @package App\Models
 *
 * @property int id
 * @property string title
 * @property string body
 * @property int category_id
 * @property string excerpt
 * @property string slug
 * @property int user_id
 * @property int reply_count
 * @property int view_count
 * @property int last_reply_user_id
 * @property int order
 * @property Category category
 * @property User user
 * @property Reply replies
 */
class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    /**
     * Define Topic belongs to Category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define Topic belongs to User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define Topic has many replies
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Query scope for order
     * 使用 scope 定义一个本地作用域的方法, 我们可以在查询构造器中调用这个方法
     * 它其实就是我们自定义的一部分查询逻辑, 我们可以将其封装在作用域中, 以便在应用中复用
     *
     * @param $query
     * @param $order
     */
    public function scopeWithOrder($query, $order): void
    {
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
    }

    /**
     * Query scope for recent replied topics
     *
     * @param $query
     * @return mixed
     */
    public function scopeRecentReplied($query): mixed
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * Query scope for recent topics
     *
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query): mixed
    {
        return $query->orderBy('created_at', 'desc');
    }
}
