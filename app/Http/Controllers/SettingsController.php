<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage settings');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = [];
        Settings::all()->each(function($s) use(&$settings) {
            $settings[$s->key] = $s->value;
        });
        return view('backend.settings.index', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'featured' => 'nullable|string',
            'comment_blacklist' => 'nullable|string',
            'comment_moderated' => 'nullable|string',
        ]);

        $data['featured'] = array_map('trim', explode(",", $data['featured']));

        // comments, split \n
        $data['comment_blacklist'] = array_map('trim', preg_split('/\n|\r\n?/', $data['comment_blacklist']));
        $data['comment_moderated'] = array_map('trim', preg_split('/\n|\r\n?/', $data['comment_moderated']));

        $keys = array_keys($data);
        foreach($keys as $key) {
            Settings::where('key', $key)->update(['value'=>$data[$key]]);
        }

        return redirect()->route('backend.settings.index')->with('status', 'Settings updated!');
    }

}
