<?php

namespace App\Models;

use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravelista\Comments\Commenter;
use App\Models\NovelChapter;
use App\Models\Series;
use Laravelista\Comments\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, Commenter, SoftDeletes;

    protected $attributes = [
        'name' => 'Guest',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'have_read'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'have_read' => 'json'
    ];


    protected $appends = ['avatar'];
    
    public function getAvatarAttribute() {
        return $this->email ? Gravatar::get($this->email) : "";
    }

    public function notifications() {
        return $this->hasMany('App\Models\Notification');
    }

    public function newNotifications() {
        return cache()->tags('notification')
            ->remember("user:$this->id:notification:unread", 60*60,
            function() {
                return $this->notifications()->where('read', false)->count();
            });
    }

    public function getNotificationsPaginations($overridePage = null) {
        return $this
            ->notifications()
            ->whereHas('parent')
            ->orderBy("read",'asc') // order by unread first
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
    public function getNotifications() {
        
        // in seconds
        $notifications_pagination = $this->getNotificationsPaginations();
            
        $notifications = collect($notifications_pagination->items())
            ->map(function($n) {
                return cache()
                ->tags(['notification', "user:$this->id:notification"])
                ->rememberForever("user:$this->id:notification:$n->id", 
                    function() use($n) {
                        $note = $n->note;
                        $link = "";
                        switch($n->notification_type) {
                            case Series::class:
                                // new series notification
                                $series = Series::findOrFail($n->notification_id);
                                $note = sprintf("New series: <strong>%s</strong> was recently published. Check it out!", $series->title);
                                $link = $series->link();
                                break;
                            
                            case NovelChapter::class:
                                // series update notification
                                $chapter = NovelChapter::with('novel')->findOrFail($n->notification_id);
                                $note = sprintf("<strong>[%s]</strong> %s was updated. Click to read!", $chapter->novel->title, $chapter->fullTitle);
                                $link = $chapter->link($chapter->novel->slug);
                                break;
                            
                            case Comment::class:
                                // comment replies notification
                                $comment = Comment::with(['commentable', 'parent'])->findOrFail($n->notification_id);
                                $parent = $comment->parent;
                                $note = sprintf('
                                <div>You\'ve got a reply to your comment:</div>
                                <div class="flex items-center text-gray-300 opacity-75 text-xs mt-1">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" transform="scale(-1,1)" transform-origin="center" />
                                    </svg>
                                    <h5 class="flex-shrink-0 font-semibold mr-2">%s</h5>
                                    <div class="flex-grow truncate">%s</div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <h5 class="font-semibold flex-shrink-0 mr-2">%s</h5>
                                    <div class="text-gray-400 truncate">%s</div>
                                </div>
                                ', 
                                $parent->commenter->name ?? $parent->guest_name,
                                $parent->comment,
                                $comment->commenter->name ?? $comment->guest_name,
                                $comment->comment
                                );
                                $link = $comment->commentable->link() . "#comment-" . $comment->id;
                                break;
                        }

                        return [
                            'id'    => $n->id,
                            'note'  => $note,
                            'read'  => $n->read,
                            'link'  => $link,
                            'type'  => $n->notification_type,
                            'created_at'=> $n->created_at
                        ];
                    });
            });

        return [$notifications_pagination, $notifications];
    }


    public function reading_list() {
        return $this->belongsToMany('App\Models\Series')->as('reading_list');
    }
    public function add_read($series_id, $chNumSlug) {
        if (!auth()->check()) return false;

        $have_read = $this->have_read;
        $key = "series_" . $series_id;

        if (isset($have_read[$key])) {
            if (in_array($chNumSlug, $have_read[$key])) return;

            // add because this doesn't exist yet
            $have_read[$key][] = $chNumSlug;
            sort($have_read[$key]);
        } else {
            $have_read[$key] = [$chNumSlug];
        }

        $this->have_read = $have_read;
        $this->save();
    }
    public function latest_read($series) {
        $key = "series_" . $series->id;
        $have_read = $this->have_read;
        if (isset($have_read[$key])) {
            $chNumSlug = array_pop($have_read[$key]);
            foreach ($series->chapters as $ch) {
                if ($ch->chNumSlug == $chNumSlug) return $ch;
            }
        } 
        return false;
    }
    public function check_read($series_id, $chNumSlug) {
        $key = "series_" . $series_id;
        $have_read = $this->have_read;
        if (isset($have_read[$key])) {
            return in_array($chNumSlug, $have_read[$key]);
        }
        return false;
    }



    // Emails can only be seen by super admin who can manage users
    public function toArray() {
        $route_name = Route::currentRouteName(); 
        if (strpos($route_name, "backend") !== false &&
            auth()->check() && 
            auth()->user()->can('manage users')) {
            $this->setAttributeVisibility();
        }
        return parent::toArray();
    }
    public function toJson($options = 0) {
        $route_name = Route::currentRouteName(); 
        if (strpos($route_name, "backend") !== false &&
            auth()->check() && 
            auth()->user()->can('manage users')) {
            $this->setAttributeVisibility();
        }
        return parent::toJson();
    }
    public function setAttributeVisibility()
    {
        $this->makeVisible(array_merge($this->fillable, $this->appends, ['email']));
    }




    
    protected static function booted()
    {
        static::deleting(function ($user) {
            // delete all notifications
            $user->notifications()->delete();

            // all posts stay, but username by [deleted]
        });
    }
}
