<?php

namespace App\Models;

use App\Models\Traits\HasNotification;
use App\Scopes\PublishedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravelista\Comments\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Series extends Model implements HasMedia
{
    use HasFactory, Commentable, SoftDeletes, HasTags, InteractsWithMedia, HasNotification;

    const LEGAL_EXCLUSIVE = "EXCLUSIVE";
    const LEGAL_LICENSED = "LICENSED";
    const LEGAL_ORIGINAL = "ORIGINAL";
    const LEGAL_OTHER = "OTHER";

    protected $casts = [
        'staffs' => 'json',
        'arcs' => 'json',
        'schedule' => 'json',
        'needs' => 'json',
        'published_at' => 'datetime'
    ];
    protected $appends = ['cover'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $hidden = ['media', 'cover'];

    public static function uniqueSlug($testSlug) {
        if (Series::where('slug', $testSlug)->exists()) {
            return $testSlug . "-1";
        }
        return $testSlug;
    }

    public function getCoverAttribute()
    {
        $cover = $this->getFirstMedia('covers');
        if ($cover == null) return "";
        if (config('media-library.disk_name') == 'digitalocean')
            return config('media-library.cdn') . "/" . $cover->getPath('cover');
        else return '//' . $cover->getUrl('cover');
    }

    public function addCover($file) {
        if (!empty($this->media->all())) {
            $this->media[0]->delete();
        }
        $this->addMedia($file)->toMediaCollection('covers');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('cover')
              ->width(200)
              ->height(300)
              ->performOnCollections('covers');
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\NovelChapter')->orderBy('chapter', 'asc')->orderBy('chapter_part', 'asc');
    }

    public function poster() {
        return $this->belongsTo('App\Models\User')->withDefault();
    }



    // reading list
    public function readers() {
        return $this->belongsToMany('App\Models\User')->as('readers');
    }

    public function link() {
        return route('frontend.series.show', $this->slug);
    }


    static public function archive($tagName = null) {
        $query = (new static)::with(['chapters:id,series_id,deleted_at,title,chapter,chapter_part', 'tags', 'media'])
            ->withCount('chapters');

        if ($tagName) $query->withAnyTags([$tagName],'tags');

        return $query->inRandomOrder()
            ->get(['id','title','slug','excerpt'])
            ->makeVisible('media');
    }



    /* 
     * Tags
     */
    public static function getTagClassName(): string
    {
        return Tag::class;
    }
    public function tags():MorphToMany
    {
        return $this
            ->morphToMany(self::getTagClassName(), 'taggable', 'taggables', null, 'tag_id')
            ->withCount('series')
            ->orderBy('series_count', 'desc')
            ->orderBy('order_column');
    }



    // When this is deleted, delete NovelChapter as well
    protected static function booted()
    {
        // only show published ones by default
        static::addGlobalScope(new PublishedScope);

        // whenever it's created or updated, check its published
        static::created(function($series) {
            Notification::notifySeries($series);
        });

        // Delete and restore chapters along with this one
        // TODO: delete notifications as well?
        static::deleting(function ($series) {
            $method = "delete";
            if ($series->isForceDeleting()) {
                $method = "forceDelete";
            }
            $series->chapters()
                ->withTrashed()
                ->get()
                ->each(function($ch) use($method) {
                    $ch->$method();
                });
        });
        static::restoring(function ($series) {
            $series->chapters()->withTrashed()->get()->each(function($ch) {
                $ch->restore();
            });
        });
    }

    public function scopeWithUnpublished($query) {
        return $query->withoutGlobalScope(PublishedScope::class);
    }
}
