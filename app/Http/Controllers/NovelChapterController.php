<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\NovelChapter;
use App\Models\User;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;

class NovelChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create novelChapter')->only(['create', 'store']);
        $this->middleware('can:edit novelChapter')->only(['index', 'edit', 'update', 'updateSlug']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = NovelChapter::query()->withUnpublished();
        
        // check if user can 'edit series' or 'edit all series'
        $user = auth()->user();
        if (!$user->can('edit all novelChapter')) {
            $query->where('poster_id', $user->id);
        }

        if (!$request->ajax()) {
            $count = [
                'all'       => $query->count(),
                'deleted'   => $query->withTrashed()->whereNotNull('deleted_at')->count()
            ];
            $novels = Series::all(['id','title']);
            return view('backend.novelChapter.index', compact('count', 'novels'));
        }

        $query = $query->with(['novel', 'poster']);
        
        // Show deleted only?
        if ($request->has('deleted')) {
            $data = $request->validate([
                'deleted'   => 'required|boolean',
            ]);
            $query->withTrashed()->whereNotNull('deleted_at');
        }

        // Only show chapters of a certain novel
        if ($request->has('novel_id')) {
            $data = $request->validate([
                'novel_id'   => 'required|numeric',
            ]);
            $query->where('series_id', $data['novel_id']);
        }

        return datatables($query) 
            ->addColumn('published', function($novelChapter) {
                return $novelChapter->published_at->toFormattedDateString();
            })
            ->addColumn('the_title', function($novelChapter) {
                $novelTitle = $novelChapter->novel ? $novelChapter->novel->title : "Deleted";
                return sprintf("
                <div class='text-xs text-gray-600'>
                    Series: %s
                </div>
                <div>%s</div>", $novelTitle, $novelChapter->title);
            })
            ->addColumn('chNum', function($novelChapter) {
                return $novelChapter->chNumSlug;
            })
            ->addColumn('action', function($novelChapter) {
                $data = [
                    'edit'  => route('backend.novelChapter.edit', $novelChapter->id)
                ];
                if ($novelChapter->novel) {
                    $data['preview'] = route('frontend.novelChapter.show', [
                        'novel_slug' => $novelChapter->novel->slug,
                        'chapter_num'=> $novelChapter->chNumSlug
                    ]);
                }
                return view('backend.components.dtPostAction', $data);
            })
            ->rawColumns(['the_title','action'])
            ->orderColumn('the_title', function ($query, $order) {
                $query->orderBy('title', $order);
            })
            ->orderColumn('chNum', function ($query, $order) {
                $query->orderBy('chapter', $order)->orderBy('chapter_part', $order);
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new NovelChapter;
        $post->published_at = Carbon::now(config('app.timezone'));
        $mode = "create";
        $post_type = "novelChapter";

        if (auth()->user()->can('edit all series')) {
            $series = Series::all();
        } else {
            $series = Series::where('poster_id', auth()->user()->id)->get();
        }
        $extra = [
            'series' => $series,
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
        $data = $request->validate([
            'title'     => 'nullable|string|max:100',
            'series_id'   => 'required|string',
            'chapter'   => 'required|numeric',
            'chapter_part'   => 'nullable|numeric',
            'nsfw'      => 'nullable|boolean',
            'content'   => 'required|string',
        ]);

        $data['poster_id'] = auth()->user()->id;

        $novelChapter = NovelChapter::create($data);

        // cover
        if (isset($data['cover']) && $data['cover']) {
            $novelChapter->addCover($request->file('cover'));
            unset($data['cover']);
        }

        // notifications for all subscribed users
        $novelChapter->notify();
        // Series::findOrFail($data['series_id'])->readers
        //     ->each(function($user)use($novelChapter) {
        //         $user->notifications()->create([
        //             'notification_id' => $novelChapter->id,
        //             'notification_type' => NovelChapter::class
        //         ]);
        //     });

        return redirect()->route('backend.novelChapter.edit', $novelChapter['id'])->with('status', 'Chapter created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NovelChapter  $novelChapter
     * @return \Illuminate\Http\Response
     */
    public function show($novel_slug, $chapter_num)
    {
        // get the novel
        $novel = Series::with('chapters')->where('slug', $novel_slug)->first(['id','title','slug']);
        if (empty($novel)) return abort(404);
        
        $chs = explode("-", $chapter_num);
        $ch_num = $chs[0];

        // novel chapter
        $novelChapter = $novel->chapters
            ->where('series_id', $novel->id)
            ->where('chapter', $ch_num);
        if (isset($chs[1])) {
            $novelChapter = $novelChapter->where('chapter_part', $chs[1]);
        }
        $novelChapter = $novelChapter->first();
        if (empty($novelChapter)) return abort(404);

        // navigation
        $chapters = $novel->chapters->all();
        $currentIndex = 0;
        foreach($chapters as $i => $ch) {
            if ($novelChapter->id == $ch->id) {
                $currentIndex = $i;
                break;
            }
        }
        $prev_ch = $currentIndex == 0 ? false : $chapters[$currentIndex-1]->chNumSlug;
        $next_ch = $currentIndex < count($chapters) - 1 ? $chapters[$currentIndex+1]->chNumSlug : false;


        // SEO
        SEOTools::setTitle("$novel->title - " . $novelChapter->fullTitle);
        SEOTools::setDescription($novel->excerpt);
        SEOTools::opengraph()->setUrl($novelChapter->link());


        return view('frontend.novelChapterShow', compact('novel','novelChapter', 'prev_ch', 'next_ch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NovelChapter  $novelChapter
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $novelChapter = NovelChapter::withUnpublished()->findOrFail($id);

        $can = $this->edit_own_permission($novelChapter);
        if ($can !== false) return $can;

        $post = $novelChapter;
        $mode = "edit";
        $posters = User::all(['id','name']);
        $post_type = "novelChapter";
        $preview = route("frontend.novelChapter.show", [
            'novel_slug' => $novelChapter->novel->slug, 
            'chapter_num' => $novelChapter->chNumSlug
        ]);
        $title = "Chapter";

        if (auth()->user()->can('edit all series')) {
            $series = Series::all();
        } else {
            $series = Series::where('poster_id', auth()->user()->id)->get();
        }
        $extra = [
            'series' => $series,
            'server_time'=> Carbon::now()->format('D, M d Y, H:i')
        ];
        return view('layouts.editPost', compact('post','mode','posters','post_type', 'preview', 'title', 'extra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NovelChapter  $novelChapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $novelChapter = NovelChapter::withUnpublished()->findOrFail($id);

        $can = $this->edit_own_permission($novelChapter);
        if ($can !== false) return $can;

        $data = $request->validate([
            'title'     => 'nullable|string|max:100',
            'series_id'   => 'required|string',
            'chapter'   => 'required|numeric',
            'chapter_part'   => 'nullable|numeric',
            'nsfw'      => 'nullable|boolean',
            'content'   => 'required|string',
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

        $novelChapter->update($data);

        return redirect()->route('backend.novelChapter.edit', $novelChapter['id'])->with('status', 'Chapter updated!');
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
                NovelChapter::withTrashed()
                    ->whereIntegerInRaw('id', $selected_ids)
                    ->get()->each(function($ch) use($method) {
                        $ch->$method();
                    });
                break;
        }
        
        return redirect()->back()->with('status', count($selected_ids) . " chapters ${method}d");
    }

    private function edit_own_permission(NovelChapter $novelChapter) {
        $user = auth()->user();
        if (!$user->can('edit all novelChapter')) {
            // get this series poster id
            if ($novelChapter->poster_id != $user->id) 
                return abort(401);
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NovelChapter  $novelChapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(NovelChapter $novelChapter)
    {
        //
    }
}
