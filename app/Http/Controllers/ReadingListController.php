<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Series;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ReadingListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SEOTools::setTitle("Reading List");

        $series = auth()->user()
            ->reading_list()
            ->get(['series.id','series.title','series.slug'])
            ->makeHidden('media');

        return view('frontend.readingList', compact('series'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id'     => 'required|string',
        ]);
        auth()->user()->reading_list()->syncWithoutDetaching($request->id);
        return redirect()->back()->with('status', 'Added to reading list.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $series = Series::findOrFail($id);
        auth()->user()->reading_list()->detach($id);
        return redirect()->back()->with('status', sprintf('Successfully deleted "%s" from the reading list.', $series->title));
    }
}
