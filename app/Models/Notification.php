<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravelista\Comments\Comment;
use Illuminate\Support\Facades\Cache;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'read' => 'boolean'
    ];

    public function parent()
    {
        return $this->morphTo(__FUNCTION__, 'notification_type', 'notification_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }


    /* 
     * TODO: Observers!
     * - Novel published
     * - NovelChapter published
     * - Comments Reply approved
     * - Cache fix on created and deleted
     */

    // Notify when new series are published/approved
    static public function notifySeries(Series $series) {
        User::all()->each(function($user) use($series) {
            $user->notifications()->create([
                'notification_id' => $series->id,
                'notification_type' => Series::class
            ]);

            // bust cache?
            cache()->tags("notification")->forget("user:$user->id:notification:unread");
        });
    }

    // Notify when new chapters are published/approved
    static public function notifyNovelChapter(NovelChapter $novelChapter) {
        $novelChapter->novel->readers->each(function($user) use($novelChapter) {
            $user->notifications()->create([
                'notification_id' => $novelChapter->id,
                'notification_type' => NovelChapter::class
            ]);

            // bust cache?
            cache()->tags("notification")->forget("user:$user->id:notification:unread");
        });
    }

    // Notify when new comments are approved/stored
    static public function notifyComment(Comment $comment) {
        // notify the parent of this comment
        $comment->commenter->notifications()->create([
            'notification_id' => $comment->id,
            'notification_type' => Comment::class,
        ]);

        // bust cache?
    }
    static public function deleteComment($comment) {
        Notification::where('notification_type', Comment::class)
        ->where('notification_id', $comment->id)
        ->delete();

        // bust cache?
    }
}
