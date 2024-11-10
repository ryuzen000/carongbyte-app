<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(Auth::id());
        return view('admin::profile.index', [
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user = User::find(Auth::id());

        $db_user = DB::table('usermeta')
            ->where('user_id', '=', Auth::id())
            ->where('key', '=', 'cr_user_foto')
            ->get();
        $foto = $db_user[0]->value;

        if ($request->exists('name')) {
            $user->name = $request->name;
        }

        if ($request->exists('email')) {
            $user->email = $request->email;
        }

        if ($request->exists('address')) {
            DB::table('usermeta')
                ->updateOrInsert(
                    ['user_id' => Auth::id(), 'key' => 'cr_user_alamat'],
                    ['user_id' => Auth::id(), 'key' => 'cr_user_alamat', 'value' => $request->address]
                );
        }

        if ($request->exists('product_image')) {
            $file = $request->file('product_image');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $path = 'uploads';
            $file->move($path, $nama_file);

            DB::table('usermeta')
                ->updateOrInsert(
                    ['user_id' => Auth::id(), 'key' => 'cr_user_foto'],
                    ['user_id' => Auth::id(), 'key' => 'cr_user_foto', 'value' => $nama_file]
                );
        }

        if ($request->frm_submit == 1) {
            $user->save();
        }

        /**
         * 
         * 
         * @source https://dcodemania.com/post/crud-application-image-upload-laravel-8
         * @source https://stackoverflow.com/questions/56231229/how-can-i-update-image-in-edit-view-in-laravel
         */
        /*
        $imageName = '';
        if ($request->hasFile('file')) {
            $imageName = time() . '.' . $request->file->extension();
            $request->file->storeAs('public/images', $imageName);
            if ($post->image) {
                Storage::delete('public/images/' . $post->image);
            }
        } else {
            $imageName = $post->image;
        }
        $postData = ['title' => $request->title, 'category' => $request->category, 'content' => $request->content, 'image' => $imageName];
        $post->update($postData);
        */

        return view('admin::profile.edit', [
            'name'  => $user->name,
            'email' => $user->email,
            'foto'  => $foto,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
