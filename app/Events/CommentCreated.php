<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('course.comments.' . $this->comment->course_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'user_id' => $this->comment->user_id,
            'created_at' => $this->comment->created_at->toDateTimeString(),
            'parent_id' => $this->comment->parent_id,
        ];
    }
}