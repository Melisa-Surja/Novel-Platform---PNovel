<?php

namespace App\Http\Controllers;

use App\Models\NovelChapter;
use App\Models\Series;
use App\Models\Settings;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Laravelista\Comments\Comment;

class PageController extends Controller
{
    public function home() {
        SEOTools::setTitle("Home");
        // title slug excerpt
        // all novels
        $novels = Series::with('media','poster:id,name')->withCount('chapters')->inRandomOrder()->get(['id','title','slug','excerpt','author'])->makeVisible('media');
        // featured
        $id = Settings::where('key', 'featured')->first()->value[0];
        $featured = $novels->find($id);
        // latest releases
        $latest = NovelChapter::getFeedItems(10);
        return view('frontend.pages.home', compact('novels', 'featured', 'latest'));
    }

    public function dashboard() {
        $user_id = auth()->user()->id;
        $series = Series::where('poster_id', $user_id)->get(['id', 'slug']);
        $series_id = $series->pluck('id');
        $chapters = NovelChapter::with('novel')->where('poster_id', $user_id)->get(['id','chapter','chapter_part', 'series_id']);
        $chapters_id = $chapters->pluck('id');

        $comments_raw = Comment::with('commenter')
            ->where('commentable_type', Series::class)
            ->whereIntegerInRaw('commentable_id', $series_id)
            ->orWhere( function($query) use ($chapters_id) {
                    return $query->where('commentable_type', NovelChapter::class)->whereIn('commentable_id', $chapters_id);
                })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10);
        $comments = collect($comments_raw->items())
            ->map(function($c) use ($series, $chapters) {
                // get links
                $link = "";
                $source = "";
                switch($c->commentable_type) {
                    case Series::class:
                        $s = $series->find($c->commentable_id);
                        $link = $s->link();
                        $source = $s->title;
                        break;
                    case NovelChapter::class:
                        $ch = $chapters->find($c->commentable_id);
                        $link = $ch->link($ch->novel->slug);
                        $source = sprintf("[%s] %s", $ch->novel->title, $ch->fullTitle);
                        break;
                }
                return [
                    'comment'   => $c->comment,
                    'commenter' => $c->commenter->name ?? $c->guest_name,
                    'link'      => $link . "#comment-" . $c->id,
                    'date'      => $c->created_at->diffForHumans(),
                    'source'    => $source
                ];
            });
        $links = $comments_raw->links();

        return view('backend.dashboard', compact("comments", "links"));
    }

    public function page($page_slug) {
        switch($page_slug) {
            case "tos":
                SEOTools::setTitle("Terms of Service");
                break;

            case "privacy":
                SEOTools::setTitle("Privacy Policy");
                break;
        }

        return view('frontend.pages.' . $page_slug);
    }
}
