<?php

namespace App\Observers;

use App\Models\Notification;
use Laravelista\Comments\Comment;

class CommentObserver
{
    public function created(Comment $comment) {
        if ($comment->approved) {
            $parent = $comment->parent;
            if (isset($parent->commenter)) {
                Notification::notifyComment($parent);
            }
        }
    }

    public function updating(Comment $comment)
    {
        // approved has changed
        if($comment->isDirty('approved')){
            $new_approved = $comment->approved; 
            $old_approved = $comment->getOriginal('approved');
            if ($new_approved) {
                // send notification when this one has a parent
                $parent = $comment->parent;
                if ($parent->commenter) {
                    Notification::notifyComment($parent);
                }
            } else {
                $parent = $comment->parent;
                if ($parent->commenter) {
                    // delete notification
                    Notification::deleteComment($comment);
                }
            }
        }
    }
    public function deleted(Comment $comment)
    {
        if ($comment->commenter)
            Notification::deleteComment($comment);
    }
}
