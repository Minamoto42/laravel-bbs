<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property string $introduction
 * @property Reply replies
 * @property Topic topics
 * @property int notification_count
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, MustVerifyEmailTrait, HasRoles, Impersonate;

    use Notifiable {
        notify as protected laravelNotify;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User has many topics.
     *
     * @return HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * User has many replies.
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Check if the user is the author of the model.
     *
     * @param object $model
     * @return bool
     */
    public function isAuthorOf(object $model): bool
    {
        return $this->id == $model->user_id;
    }
    /**
     * Send the notification to the user.
     *
     * @param mixed $instance
     * @return void
     */
    public function notify($instance): void
    {
        // If the notification to him is the same as the current user, no need to notify him.
        // If the notification instance is VerifyEmail, no need to notify him.
        if ($this->id == Auth::id() && get_class($instance) == 'Illuminate\Auth\Notifications\VerifyEmail') {
            return;
        }

        // Only notification channel is database, we will send the notification.
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead(): void
    {
        $this->notification_count = 0;
        $this->save();

        // This is a method of the Notifiable trait from Laravel.
        // It will mark all the unread notifications as read.
        $this->unreadNotifications->markAsRead();
    }
}
