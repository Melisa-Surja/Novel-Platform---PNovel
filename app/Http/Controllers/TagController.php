<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\SEOTools;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:edit all series')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Tag::query()->withCount('series')->withType('tags');

        if (!$request->ajax()) {
            $create = false;
            return view('backend.tag.index', compact('create'));
        }

        return datatables($query)
            ->addColumn('name', function($tag) {
                return $tag->name;
            })
            ->addColumn('action', function($tag) {
                $edit = route('backend.tag.edit', $tag->id);
                $preview = route('frontend.tag.show', $tag->slug);
                return view('backend.components.dtPostAction', compact('edit', 'preview'));
            })
            ->rawColumns(['action'])
            ->make();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $tag = Tag::where("slug->en", $slug)->withType('tags')->first();
        if (empty($tag)) return abort(404);

        $novels = Series::archive($tag->name);
        $title = sprintf("%s: %d Novels", $tag->name, $novels->count());

        SEOTools::setTitle($title);
        SEOTools::opengraph()->setUrl(url()->current());

        return view('frontend.novelsArchive', compact('novels', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {        
        $post = $tag;
        $mode = "edit";
        $post_type = "tag";
        $preview = route("frontend.tag.show", $tag->slug);
        $title = "Tag";
        $extra = ['series' => $tag->series];
        return view('layouts.editPost', compact('post','mode','post_type', 'preview', 'title', 'extra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'slug'     => 'required|string|max:100'
        ]);

        if (isset($data['name'])) {
            $tag->setTranslation('name', 'en', $data['name']);
        }
        if (isset($data['slug'])) {
            $tag->setTranslation('slug', 'en', $data['slug']);
        }

        if (!empty($data)) $tag->save();

        return redirect()->back()->with('status', 'Tag updated!');
    }
    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'update_method' => 'required|string',
            'selected_ids'  => 'required|string',
        ]);
        $selected_ids = explode(",", $request->selected_ids);

        $method = $request->update_method;
        switch($method) {
            case 'delete':
                Tag::whereIntegerInRaw('id', $selected_ids)
                    ->get()->each(function($tag) use($method) {
                        $tag->$method();
                    });
                break;
        }
        
        return redirect()->back()->with('status', count($selected_ids) . " tags ${method}d");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
