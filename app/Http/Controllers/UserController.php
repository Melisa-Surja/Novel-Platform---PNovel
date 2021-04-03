<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Artesaos\SEOTools\Facades\SEOTools;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['edit_self_front', 'update_self_front']);
        $this->middleware('can:manage users')->except(['edit_self_front', 'update_self_front']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax())
            return view('backend.user.index');

        return datatables(User::query())
            ->addColumn('role', function($user) {
                return implode(", ", $user->getRoleNames()->all());
            })
            ->addColumn('action', function($user) {
                $edit = route('backend.user.edit', $user->id);
                $preview = "";
                return view('backend.components.dtPostAction', compact('edit', 'preview'));
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new User;
        $mode = "create";
        $post_type = "user";
        $extra = ['roles' => Role::all('name')->pluck('name')];
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
            'name'          => 'nullable|string|max:50|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6',
            'role'          => 'required|string',
        ]);

        $this->process_password($data);

        $user = User::create($data);
        $user->syncRoles([$data['role']]);

        return redirect()->route('backend.user.edit', $user['id'])->with('status', 'User created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $post = $user;
        $mode = "edit";
        $post_type = "user";
        $preview = '';
        $extra = ['roles' => Role::all('name')->pluck('name')];
        return view('layouts.editPost', compact('post','mode','post_type', 'preview', 'extra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:50',
            'email'         => 'required|email',
            'password'      => 'nullable|string|min:6',
            'confirm_password' => 'nullable|string|min:6',
            'role'          => 'required|string',
        ]);

        // check password if the same
        $this->process_password($data);

        $user->syncRoles([$data['role']]);
        $user->update($data);

        return redirect()->route('backend.user.edit', $user['id'])->with('status', 'User Data updated!');
    }

    private function process_password(&$data) {
        if (isset($data['password'])) {
            if ($data['password'] == $data['confirm_password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // error
                return redirect()
                    ->back()
                    ->withErrors(['Password and Confirm Password fields must be the same.'])
                    ->withInput();
            }
        }
        if (empty($data['password'])) unset($data['password']);
        unset($data['confirm_password']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }




    public function edit_self_front() {

        $user = auth()->user();
        
        // SEO
        SEOTools::setTitle($user->name);
        SEOTools::opengraph()->setUrl(url()->current());

        return view("frontend.userEdit", compact('user'));
    }
    public function update_self_front(Request $request)
    {
        $data = $request->validate([
            'new_password'          => 'required|string|min:6',
            'confirm_password'      => 'required|string|min:6'
        ]);

        $user = auth()->user();

        // if old password is the same, can change to new password
        if ($request->new_password == $request->confirm_password) {
            // change to new password
            $user->update(['password' => Hash::make($request->new_password)]);

            return redirect()->back()
                ->with('status', 'Password updated!');
        } else {
            return redirect()->back()
                ->withErrors(['New password and Confirm new password must be the same.'])
                ->withInput();
        }
    }
}
