<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    use HasFactory;

    /**
     * 可以批量赋值的字段
     *
     * 这里除了 'message' 外，增加了 'parent_id'，
     * 如果需要在 store 中一次性写入 topic_id，也可将 'topic_id' 加入其中。
     */
    protected $fillable = [
        'message',
        'parent_id',
        // 'topic_id', // 如有需要，可以在此处开启
    ];

    /**
     * 所属话题
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 作者（用户）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 父回复（若 parent_id=0 则表示无父回复，为顶级回复）
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 子回复（楼中楼）
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * 按创建时间降序获取回复
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
