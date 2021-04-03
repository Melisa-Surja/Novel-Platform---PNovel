<?php

namespace App\Models;

use App\Models\Traits\HasNotification;
use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelista\Comments\Commentable;

use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class NovelChapter extends Model implements Feedable
{
    use HasFactory, Commentable, SoftDeletes, HasNotification;

    protected $guarded = [];
    protected $casts = [
        'published_at' => 'datetime'
    ];
    protected $appends = ['chNum','fullTitle','chNumSlug'];

    public function getFullTitleAttribute() {
        // ch number + parts
        $title = $this->title;
        if (empty($title)) {
            $title = "Chapter " . $this->chapter;
            if ($this->chapter_part && ($this->chapter_part != 0)) 
                $title .= "." . $this->chapter_part;
            $title .= " " . $this->title;
        } else {
            $title = $this->chNum . " " . $this->title;
        }
        return $title;
    }
    public function fullTitleHTML() {

    }
    public function getChNumAttribute() {
        $ch = $this->chapter;
        if ($this->chapter_part && ($this->chapter_part != 0)) {
            $ch .= "." . $this->chapter_part;
        } else {
            $ch .= ".";
        }
        return $ch;
    }

    public function getChNumSlugAttribute() {
        return $this->chapter . (($this->chapter_part && ($this->chapter_part != 0)) ? "-".$this->chapter_part : "");
    }

    public function novel()
    {
        return $this->belongsTo('App\Models\Series', 'series_id', 'id');
    }

    public function poster() {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function link($novel_slug = "") {
        if (empty($novel_slug) && !$this->novel) return "";
        return route('frontend.novelChapter.show', [
            'novel_slug' => empty($novel_slug) ? $this->novel->slug : $novel_slug,
            'chapter_num'=> $this->chNumSlug
        ]);
    }


    // Feeds
    public function toFeedItem():FeedItem
    {
        return FeedItem::create([
            'id'        => $this->id,
            'title'     => $this->fullTitle,
            'updated'   => $this->published_at,
            'summary'   => $this->novel->excerpt,
            'category'  => $this->novel->title,
            'link'      => $this->link(),
            'author'    => $this->poster->name,
        ]);
    }
    public static function getFeedItems($limit = 30)
    {
        $latest = NovelChapter::with('novel','poster')
            ->whereHas('novel')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get(['id','title','chapter','chapter_part', 'series_id', 'published_at','poster_id']);
        return $latest;
    }

    protected static function booted()
    {
        // only show published ones by default
        static::addGlobalScope(new PublishedScope);

        // whenever it's created or updated, check its published
        static::created(function($series) {
        });
        static::updated(function($series) {
        });
    }

    public function scopeWithUnpublished($query) {
        return $query->withoutGlobalScope(PublishedScope::class);
    }
}
