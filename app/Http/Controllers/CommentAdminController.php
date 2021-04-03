<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravelista\Comments\Comment;

class CommentAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:edit all comment');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax())
            return view('backend.comment.index');

        // check if user can 'edit series' or 'edit all series'
        $query = Comment::query();
        $user = auth()->user();
        if (!$user->can('edit all comment')) {
            $query->where('commenter_id', $user->id);
        }

        return datatables($query)
            ->addColumn('created', function($comment) {
                return $comment->created_at->toFormattedDateString();
            })
            ->addColumn('commenterName', function($comment) {
                return $comment->commenter->name ?? $comment->guest_name . " (Guest)";
            })
            ->addColumn('action', function($comment) {
                $data = [
                    'edit' => route('backend.comment.edit', $comment->id)
                ];
                $parent = $comment->commentable;
                if ($parent) {
                    $link = $parent->link();
                    if (!empty($link)) $data['preview'] = $link . "/#comment-" . $comment->id;
                }
                return view('backend.components.dtPostAction', $data);
            })
            ->rawColumns(['action'])
            ->make();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $post = $comment;

        $mode = "edit";
        $post_type = "comment";
        $parent = $post->commentable_type::find($post->commentable_id);
        $preview = $parent->link() . "/#comment-" . $post->id; 
        return view('layouts.editPost', compact('post','mode','post_type', 'preview'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'comment'   => 'required|string',
            'approved'   => 'nullable|boolean',
        ]);

        $data['approved'] = empty($data['approved']) ? false : true;

        $comment->update($data);
        
        return redirect()->route('backend.comment.edit', $comment['id'])->with('status', 'Comment updated!');
    }

    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'update_method'     => 'required|string',
            'selected_ids'   => 'required|string',
        ]);

        $selected_ids = explode(",", $request->selected_ids);
        $method = $request->update_method;
        $comments = Comment::whereIntegerInRaw('id', $selected_ids)
            ->get()->each(function($comment) use($method) {
                switch($method) {
                    case "approve":
                        $comment->update(['approved'=>true]);
                        break;
                    case "unapprove":
                        $comment->update(['approved'=>false]);
                        break;
                    case "delete":
                        $comment->forceDelete();
                        break;
                }
            });

        return redirect()->route('backend.comment.index')->with('status', count($selected_ids) . " comments " . $request->update_method . "d");
    }
}
