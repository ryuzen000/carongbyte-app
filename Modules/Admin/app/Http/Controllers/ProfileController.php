<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Hash;

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
        /**
         * 
         * Untuk project selanjutnya
         * 
         * @source https://laracasts.com/discuss/channels/code-review/saving-data-on-click-with-ajax
         */
        /*$request->validate([
            'name'          => 'max:255',
            'email'         => 'unique:users|max:255',
            'product_image' => 'mimes:jpg|max:2048',
        ]);*/

        $validated = $request->validate([
            'email'         => 'email:rfc,dns|max:255',
            'product_image' => 'mimes:jpg|max:2048',
        ]);

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
            $file      = $request->file('product_image');
            $file_name = time() . "_" . str_replace(" ", "-", strtolower($user->name)) . ".jpg"; /*$file->getClientOriginalName();*/
            $path      = 'uploads';
            $file->move($path, $file_name);

            DB::table('usermeta')
                ->updateOrInsert(
                    ['user_id' => Auth::id(), 'key' => 'cr_user_foto'],
                    ['user_id' => Auth::id(), 'key' => 'cr_user_foto', 'value' => $file_name]
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

    public function change_password(Request $request)
    {
        $user = User::find(Auth::id());

        /**
         * 
         * Validate all input fields
         * 
         * @source https://stackoverflow.com/questions/50074815/laravel-check-for-old-password-when-change-new-password
         * 
         */
        /*$validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $validated = $request->validate([
            'old_password'         => 'email:rfc,dns|max:255',
            'new_password' => 'mimes:jpg|max:2048',
        ]);*/

        $pesan = null;

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            //$request->session()->flash('success', 'Password changed');
            return redirect()->route('admin.profile.index');
            $pesan = "Password lama benar!";
        } else {
            /*$request->session()->flash('error', 'Password does not match');
            return redirect()->route('admin.profile.change-password');*/
            $pesan = "Password lama salah!";
        }

        return view('admin::profile.change_password', ['pesan' => $pesan]);
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
