<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Honeypot\ProtectAgainstSpam;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Laravelista\Comments\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');

        if (Config::get('comments.guest_commenting') == true) {
            $this->middleware('auth')->except(['store', 'reply']);
            $this->middleware(ProtectAgainstSpam::class)->only('store');
        } else {
            $this->middleware('auth');
        }
    }

    /**
     * Creates a new comment for given model.
     */
    public function store(Request $request)
    {
        // If guest commenting is turned off, authorize this action.
        if (Config::get('comments.guest_commenting') == false) {
            Gate::authorize('create-comment', Comment::class);
        }

        // Define guest rules if user is not logged in.
        if (!Auth::check()) {
            $guest_rules = [
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|string|email|max:255',
            ];
        }

        // Merge guest rules, if any, with normal validation rules.
        Validator::make($request->all(), array_merge($guest_rules ?? [], [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|string|min:1',
            'message' => 'required|string'
        ]))->validate();

        // check if blacklisted
        if ($this->isBlacklisted($request->message))
            return Redirect::to(URL::previous());

        $model = $request->commentable_type::findOrFail($request->commentable_id);

        $commentClass = Config::get('comments.model');
        $comment = new $commentClass;

        if (!Auth::check()) {
            $comment->guest_name = $request->guest_name;
            $comment->guest_email = $request->guest_email;
        } else {
            $comment->commenter()->associate(Auth::user());
        }

        $comment->commentable()->associate($model);
        $comment->comment = $request->message;

        //
        // $comment->approved = !Config::get('comments.approval_required');
        $comment->approved = $this->isApproved($comment->comment);

        $comment->save();

        return Redirect::to(URL::previous() . '#comment-' . $comment->getKey())->with('comment_status', 'Comment posted!');
    }

    private function isBlacklisted(String $message):bool {
        $blacklisted_keywords = Settings::where('key','comment_blacklist')->first()->value;
        foreach ($blacklisted_keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isApproved(String $message):bool {
        $moderated_keywords = Settings::where('key','comment_moderated')->first()->value;
        foreach ($moderated_keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return false;
        }
        return true;
    }

    /**
     * Updates the message of the comment.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('edit-comment', $comment);

        Validator::make($request->all(), [
            'message' => 'required|string'
        ])->validate();

        $comment->update([
            'comment' => $request->message
        ]);

        return Redirect::to(URL::previous() . '#comment-' . $comment->getKey());
    }

    /**
     * Deletes a comment.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete-comment', $comment);

        if (Config::get('comments.soft_deletes') == true) {
			$comment->delete();
		}
		else {
			$comment->forceDelete();
		}

        return Redirect::back();
    }

    /**
     * Creates a reply "comment" to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        // Gate::authorize('reply-to-comment', $comment);
        Validator::make($request->all(), [
            'message' => 'required|string'
        ])->validate();
        
        if ($this->isBlacklisted($request->message))
            return Redirect::to(URL::previous());

        $commentClass = Config::get('comments.model');
        $reply = new $commentClass;

        if (!Auth::check()) {
            Validator::make($request->all(), [
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|string|email|max:255',
            ])->validate();
            $reply->guest_name = $request->guest_name;
            $reply->guest_email = $request->guest_email;
        } else {
            $reply->commenter()->associate(Auth::user());
        }

        $reply->commentable()->associate($comment->commentable);
        $reply->parent()->associate($comment);
        $reply->comment = $request->message;
        $reply->approved = $this->isApproved($reply->comment);
        // $reply->approved = !Config::get('comments.approval_required');
        $reply->save();

        return Redirect::to(URL::previous() . '#comment-' . $reply->getKey());
    }
}
