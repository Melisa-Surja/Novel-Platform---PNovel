<?php

namespace App\Http\Controllers;

use App\Models\NovelChapter;
use App\Models\Series;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SeriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create series')->only(['create', 'store']);
        $this->middleware('can:edit series')->only(['index', 'edit', 'update', 'updateSlug']);
        $this->middleware('can:edit all series')->only(['bulkUpdate']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Series::query()->with('media')->withUnpublished();

        // check if user can 'edit series' or 'edit all series'
        $user = auth()->user();
        if (!$user->can('edit all series')) {
            $query->where('poster_id', $user->id);
        }

        if (!$request->ajax()) {
            $count = [
                'all'       => $query->count(),
                'deleted'   => $query->withTrashed()->whereNotNull('deleted_at')->count()
            ];
            return view('backend.series.index', compact('count'));
        }

        $query = $query->with('poster');

        // Show deleted only?
        if ($request->has('deleted')) {
            $data = $request->validate([
                'deleted'   => 'required|boolean',
            ]);
            $query->withTrashed()->whereNotNull('deleted_at');
        }

        return datatables($query)
            ->addColumn('published', function($novelChapter) {
                return $novelChapter->published_at->toFormattedDateString();
            })
            ->addColumn('cover_image', function($series) {
                return "<img src='$series->cover' style='max-width:40px' />";
            })
            ->addColumn('action', function($series) {
                $edit = route('backend.series.edit', $series->id);
                $preview = route('frontend.series.show', $series->slug);
                return view('backend.components.dtPostAction', compact('edit', 'preview'));
            })
            ->rawColumns(['cover_image', 'action'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Series;
        $post->published_at = Carbon::now(config('app.timezone'));
        $mode = "create";
        $post_type = "series";

        $tags = Tag::getWithType('tags')->unique('name')->map(function($tag) {
            return $tag->name;
            // return ['label' => $tag->name, 'slug' => $tag->slug];
        })->values();

        $extra = [
            'tags'      => $tags,
            'server_time'=> Carbon::now()->format('D, M d Y, H:i')
        ];

        return view('layouts.editPost', compact('post','mode','post_type', 'extra'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate_form($request);
        $request->validate([
            'title'     => 'unique:series'
        ]);

        $data['slug'] = Series::uniqueSlug(Str::slug(substr($data['title'], 0, 50)));
        $data['poster_id'] = auth()->user()->id;

        $tags = $data['tags'];
        unset($data['tags']);

        $cover = "";
        if (isset($data['cover']) && $data['cover']) {
            $cover = $data['cover'];
            unset($data['cover']);
        }

        $series = Series::create($data);

        // cover
        if ($cover) {
            $series->addCover($request->file('cover'));
        }

        // tags
        if (!empty($tags)) {
            $series->syncTagsWithType($tags, 'tags');
        }

        return redirect()->route('backend.series.edit', $series['id'])->with('status', 'Series created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $series = Series::with(['chapters', 'tags', 'media'])->where('slug', $slug)->first();
        if (empty($series)) return abort(404);

        $series = $series->makeVisible('media');
        $tags = $series->tagsWithType('tags');
        
        // SEO
        SEOTools::setTitle($series->title);
        SEOTools::setDescription($series->excerpt);
        SEOTools::opengraph()->setUrl(url()->current());


        return view('frontend.seriesShow', compact('series', 'tags'));
    }

    public function archive()
    {
        $novels = Series::archive();

        $title = $novels->count() . " Novels";

        // SEO
        SEOTools::setTitle($title);
        SEOTools::opengraph()->setUrl(url()->current());

        return view('frontend.novelsArchive', compact('title', 'novels'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $series = Series::withUnpublished()->findOrFail($id);

        $can = $this->edit_own_permission($series);
        if ($can !== false) return $can;

        $post = $series;
        $mode = "edit";
        $posters = User::all(['id','name']);
        $post_type = "series";
        $preview = route("frontend.series.show", $post->slug);
        $selected = $series->tagsWithType('tags')->pluck('name')->unique()->values();
        $tags = Tag::getWithType('tags')->unique('name')->map(function($tag) {
            return $tag->name;
            // return ['label' => $tag->name, 'slug' => $tag->slug];
        })->values();

        $extra = [
            'selected'  => $selected,
            'tags'      => $tags,
            'server_time'=> Carbon::now()->format('D, M d Y, H:i')
        ];

        return view('layouts.editPost', compact('post','mode','posters','post_type', 'preview', 'extra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $series = Series::withUnpublished()->findOrFail($id);

        $can = $this->edit_own_permission($series);
        if ($can !== false) return $can;

        $data = $this->validate_form($request);
        
        // cover
        if (isset($data['cover'])) {
            $series->addCover($request->file('cover'));
            unset($data['cover']);
        }

        // tags
        if (!empty($data['tags'])) {
            $series->syncTagsWithType($data['tags'], 'tags');
        }
        unset($data['tags']);

        //
        $series->update($data);

        return redirect()->back()->with('status', 'Series updated!');
    }

    public function bulkUpdate(Request $request) {
        $data = $request->validate([
            'update_method' => 'required|string',
            'selected_ids'  => 'required|string',
        ]);
        $selected_ids = explode(",", $request->selected_ids);

        $method = $request->update_method;
        switch($method) {
            case 'restore':
            case 'delete':
            case 'forceDelete':
                Series::withTrashed()
                    ->whereIntegerInRaw('id', $selected_ids)
                    ->get()
                    ->each(function($s) use($method) {
                        $s->$method();
                    });
                break;
        }
        
        return redirect()->back()->with('status', count($selected_ids) . " series ${method}d");
    }

    public function updateSlug(Request $request, $id) {
        // $can = $this->edit_own_permission(Series::find($id));
        // if ($can !== false) return $can;

        $data = $request->validate([
            'slug'     => 'required|string|max:50',
        ]);
        $series = Series::withUnpublished()->where('id', $id)->first();

        if ($series->slug != $data['slug']) {
            $data['slug'] = Series::uniqueSlug($data['slug']);
        }

        return $series->update($data);
    }

    private function edit_own_permission(Series $series) {
        $user = auth()->user();
        if (!$user->can('edit all series')) {
            // get this series poster id
            if ($series->poster_id != $user->id) 
                return abort(401);
        }
        return false;
    }

    private function validate_form(Request $request) {
        $data = $request->validate([
            'cover'     => 'nullable|image',
            'title'     => 'required|string|max:100',
            'summary'   => 'required|string',
            'excerpt'   => 'required|string|max:500',
            'author'    => 'required|string|max:50',
            'translator'=> 'nullable|string',
            'editor'    => 'nullable|string',
            'arcs'      => 'nullable',
            'schedule'  => 'nullable|array',
            'completed' => 'nullable|boolean',
            'poster_id' => 'nullable',
            'tags'      => 'nullable|string',
            'legal_status'=> 'nullable|string|max:50',
            'date'      => 'required|date',
            'hours'     => 'required|string',
            'minutes'   => 'required|string',
        ]);

        $data['published_at'] = Carbon::createFromFormat(
                'D M d Y H:i', 
                $data['date'] . " " . $data['hours'] . ":" . $data['minutes']
            );
        unset($data['date']);
        unset($data['hours']);
        unset($data['minutes']);

        // staffs
        $data['staffs'] = [
            'translator' => $data['translator'],
            'editor' => $data['editor'],
        ];
        $data['completed'] = isset($data['completed']) ? true : false;
        unset($data['translator']);
        unset($data['editor']);

        if (!empty($data['tags'])) $data['tags'] = explode(",", $data['tags']);

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series)
    {
        //
    }








    // CG Stuff
    public function cg_check(Request $request) {
        $data = $request->validate([
            'novels'    => 'required|array',
            'p'         => 'required|string'
        ]);
        if ($data['p'] !== "28Vdh@zTy4TNrt@H") return abort(401);
            
        $total = 0;
        $missing_novels = [];
        $missing_chapters = [];
        $current_acronyms = Tag::getWithType('acronym')->pluck('slug')->all();
        foreach ($request->novels as $acronym => $ch) {
            // tag acronym Series
            $series = Series::with('chapters')->withAnyTags([$acronym], 'acronym')->first();
            if (in_array($acronym, $current_acronyms) && $series) {
                // check per chapters
                $available_chapters = $series->chapters->map(function($ch) {
                    return $ch->chNumSlug;
                })->all();
                foreach ($ch as $chSlug => $chID) {
                    if (in_array($chSlug, $available_chapters))
                        unset($ch[$chSlug]);
                }
            } else {
                // add all the chapters
                $missing_novels[] = $acronym;

                // 1 is for the new novel
                $total++;
            }
            if (!empty($ch)) {
                $missing_chapters[$acronym] = $ch;
                $total += count($ch);
            }
        }

        return [
            'novels'    => $missing_novels, 
            'chapters'  => $missing_chapters,
            'total'     => $total,
        ];
    }
    public function cg_series_store(Request $request) {
        $data = $request->validate([
            'users'     => 'nullable|array',
            'novels'    => 'required|array',
            'p'         => 'required|string'
        ]);

        $new_novels = $data['novels'];
        $pass = md5(hash('sha256', $new_novels[array_keys($new_novels)[0]]['created_at']));
        if ($data['p'] !== $pass) return abort(401);

        // new users
        $new_users = $data['users'];
        if (!empty($new_users)) {
            // make new users
            foreach ($new_users as $user) {
                // check if it already exist
                if (User::where('email', $user['email'])->count() > 0) continue;

                $create = array_merge($user, [
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(8)),
                    'remember_token'    => Str::random(10)
                ]);
                User::create($create)->assignRole('Poster');
            }
        }

        // new novels
        foreach ($new_novels as $acronym => $novel) {
            // check if series with that acronym already exist
            if (Series::withAnyTags([$acronym], 'acronym')->count() > 0) continue;

            // staffs (from editors)
            $staffs = ['editors'=> $novel['editors']];
            unset($novel['editors']);

            // excerpt from summary
            $excerpt = substr($novel['summary'], 0, 300);

            // cover
            $cover = $novel['cover'];
            unset($novel['cover']);

            // tags
            $tags = $novel['tags'] ?? [];
            unset($novel['tags']);

            // get author
            $author = User::where('email', $novel['author'])->first();
            unset($novel['author']);
            $create = array_merge($novel, [
                'slug'      => Series::uniqueSlug(Str::slug($novel['title'])),
                'excerpt'   => $excerpt,
                'author'    => $author->name,
                'staffs'    => $staffs,
                'arcs'      => [],
                'poster_id' => $author->id,
                'legal_status'=> Series::LEGAL_ORIGINAL
            ]);
            $series = Series::create($create);

            // notification?

            // tags and acronyms
            if (!empty($tags))
                $series->syncTagsWithType($tags, 'tags');
            $series->syncTagsWithType([$acronym], 'acronym');

            // cover
            if (!empty($cover)) {
                $series->addMediaFromUrl($cover)->toMediaCollection('covers');
                // $series->addMediaFromBase64($cover)->toMediaCollection('covers');
            }
        }

        return response('Series and Users Stored');
    }

    public function cg_chapters_store(Request $request) {
        $data = $request->validate([
            'chapters'  => 'required|array',
            'p'         => 'required|string'
        ]);
        $sent_chapters = $request->chapters;
        $novel_acronym = array_keys($sent_chapters)[0];
        $pass_first_ch = $sent_chapters[$novel_acronym];
        $pass_first_ch_slug = array_keys($pass_first_ch)[0];
        $pass = md5(hash("sha256", $pass_first_ch[$pass_first_ch_slug]['created_at']));
        if ($data['p'] !== $pass) return abort(401);

        $test = [];
        foreach ($sent_chapters as $acronym => $chapters) {
            // get current chapters that exist, the ones that don't exist will be added
            $series = Series::with('chapters')->withAnyTags([$acronym], 'acronym')->first();
            if (!$series) continue;

            $current_chapters = $series->chapters->map(function($ch) {
                return $ch->chNumSlug;
            })->all();
            
            foreach ($chapters as $chNumSlug => $chapter) {
                // ch already exist
                if (in_array($chNumSlug, $current_chapters)) continue;

                // chapter number
                $chNum = explode("-", $chNumSlug);
                $ch = $chNum[0];
                $chapter['nsfw'] = boolval($chapter['nsfw']);
                $create = [
                    'chapter'   => $ch,
                    'series_id' => $series->id,
                    'poster_id' => $series->poster_id
                ];
                if (isset($chNum[1])) $create['chapter_part'] = $chNum[1];

                // strip all html tags
                $chapter['content'] = strip_tags($chapter['content']);

                // take all [tn content="popup content"]visible text[/tn]
                $chapter['content'] = preg_split(
                    '/\[tn (.+?)\[\/tn\]/ms', 
                    $chapter['content'], 
                    -1, 
                    PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
                );
                // convert text to delta
                // {
                //  ops: [
                //     { insert: "text" },
                //     { insert: "visible text", attributes: {popup: "popup content"} }
                //  ]
                // }
                $chapter['content'] = array_map(function($str) {
                    $isPopup = preg_match('/^content=["\'](.+)["\']\](.+)/ms', $str, $matches);
                    if ($isPopup === 1) {
                        return [
                            'insert' => $matches[1],
                            'attributes' => [
                                'popup' => $matches[2]
                                ]
                        ];
                    } else {
                        return ['insert' => $str];
                    }
                }, $chapter['content']);
                $chapter['content'] = json_encode(['ops'=>$chapter['content']]);

                // make ch!!
                $create = array_merge($chapter, $create);
                NovelChapter::create($create);

                // $test[] = $chapter['content'];
            }
        }
        // return $test;
        return response('Chapters Stored');
    }
}
