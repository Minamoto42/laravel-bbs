<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicReplied extends Notification
{
    use Queueable;

    public Reply $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        // return ['mail'];
        return ['database'];
    }

    /**
     * Set database notification data.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase(mixed $notifiable): array
    {
        $topic = $this->reply->topic;
        $link = $topic->slug . '#reply' . $this->reply->id;

        // 存入数据库里的数据
        // 这些数据足以描述此通知所需的信息, 谁回复了哪个帖子, 回复的内容是什么
        return [
            'reply_id' => $this->reply->id, // 回复的内容
            'reply_content' => $this->reply->content, // 回复的内容
            'user_id' => $this->reply->user_id, // 谁
            'user_name' => $this->reply->user->name, // 谁
            'user_avatar' => $this->reply->user->avatar, // 谁
            'topic_link' => $link, // 哪个帖子
            'topic_id' => $topic->id, // 哪个帖子
            'topic_title' => $topic->title // 哪个帖子
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
