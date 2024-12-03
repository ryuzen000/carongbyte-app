<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager as Image;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


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
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        /**
         * 
         * Untuk project selanjutnya
         * 
         * @source https://laracasts.com/discuss/channels/code-review/saving-data-on-click-with-ajax
         * @source https://laravel.com/docs/11.x/csrf
         */
        /*$request->validate([
            'name'          => 'max:255',
            'email'         => 'unique:users|max:255',
            'product_image' => 'mimes:jpg|max:2048',
        ]);*/

        $user    = User::find(Auth::id());
        $db_user = DB::table('usermeta')
            ->where('user_id', '=', Auth::id())
            ->where('key', '=', 'cr_user_foto')
            ->get();
        $foto    = $db_user[0]->value;

        if ($request->isMethod('post')) {
            $user_id   = Auth::id();
            $messages  = [
                'name.required'       => 'Nama tidak boleh kosong',
                'email.required'      => 'Alamat email tidak boleh kosong!',
                'email.unique'        => 'Alamat email ini sudah terdaftar!',
                'email.email'         => 'Alamat email tidak valid!',
                'product_image.mimes' => 'Format foto harus JPG/PNG!',
            ];
            $validated = $request->validate([
                'name'          => 'required|max:255',
                'email'         => "required|unique:users,email,{$user_id}|email:rfc,dns|max:255",
                'product_image' => 'mimes:jpg,png|max:2048',
            ], $messages);

            /*if ($request->exists('name')) {
                
            }*/
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->save();

            DB::table('usermeta')
                ->updateOrInsert(
                    ['user_id' => Auth::id(), 'key' => 'cr_user_alamat'],
                    ['user_id' => Auth::id(), 'key' => 'cr_user_alamat', 'value' => $request->address]
                );

            if ($request->exists('product_image')) {
                /*$file      = $request->file('product_image');
                $file_name = time() . "_" . str_replace(" ", "-", strtolower($user->name)) . ".jpg"; /*$file->getClientOriginalName();*/
                /*$path      = 'uploads';
                $file->move($path, $file_name);*/

                $manager  = new Image(new Driver());
                $image    = $manager->read($request->file('product_image'));
                $filename = time() . '_300x300.webp';
                $image->scale(300);
                $image->toWebp(65)->save(public_path('uploads/') . $filename);

                /*$file = $request->file('product_image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $img = Image::make($file);
                if (Image::make($file)->width() > 720) {
                    $img->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $img->save(public_path('upload_file/') . $filename);*/

                DB::table('usermeta')
                    ->updateOrInsert(
                        ['user_id' => Auth::id(), 'key' => 'cr_user_foto'],
                        ['user_id' => Auth::id(), 'key' => 'cr_user_foto', 'value' => $filename]
                    );
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

            $request->session()->flash('success', 'Data berhasil diubah!');
            return redirect()->route('admin.profile.index');
        }

        return view('admin::profile.edit', [
            'name'  => $user->name,
            'email' => $user->email,
            'foto'  => $foto,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:255',
            'email'         => "required|unique:users,email,{$user_id}|email:rfc,dns|max:255",
            'product_image' => 'mimes:jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.profile.edit')
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        // Retrieve a portion of the validated input...
        $validated = $validator->safe()->only(['name', 'email']);
        $validated = $validator->safe()->except(['name', 'email']);

        // Store the blog post...
        $manager  = new Image(new Driver());
        $image    = $manager->read($request->file('product_image'));
        $filename = time() . '_300x300.webp';
        $image->scale(300);
        $image->toWebp(65)->save(public_path('uploads/') . $filename);

        DB::table('usermeta')
            ->updateOrInsert(
                ['user_id' => $user_id, 'key' => 'cr_user_foto'],
                ['user_id' => $user_id, 'key' => 'cr_user_foto', 'value' => $filename]
            );

        $request->session()->flash('success', 'Data berhasil diubah!');
        return redirect()->route('admin.profile.index');
    }

    public function edit_ajax(Request $request)
    {
        $user = User::find(Auth::id());

        if ($request->exists('name')) {
            $user->name = $request->name;
            $user->save();
            return response()->json([
                'name'    => $request->name,
                'message' => 'Data berhasil di ubah!',
            ]);
        } else {
            return response()->json([
                'message' => 'Error!',
            ]);
        }
    }

    public function change_password(Request $request)
    {
        $user = User::find(Auth::id());

        /**
         * 
         * Validate all input fields
         * 
         * @source https://stackoverflow.com/questions/50074815/laravel-check-for-old-password-when-change-new-password
         * @source https://www.malasngoding.com/notifikasi-dengan-session-laravel/
         */
        /*$validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $validated = $request->validate([
            'old_password'         => 'email:rfc,dns|max:255',
            'new_password' => 'mimes:jpg|max:2048',
        ]);*/

        if (
            $request->exists('old_password') &&
            $request->exists('new_password')
        ) {
            if (Hash::check($request->new_password, $user->password)) {
                $request->session()->flash('error', 'Password baru dengan password lama sama');
                return redirect()->route('admin.profile.change-password');
            } else {
                if ($request->new_password == $request->confirm_password) {
                    if (Hash::check($request->old_password, $user->password)) {
                        $user->fill([
                            'password' => Hash::make($request->new_password)
                        ])->save();

                        $request->session()->flash('success', 'Password changed');
                        return redirect()->route('admin.profile.change-password');
                    } else {
                        $request->session()->flash('error', 'Password does not match');
                        return redirect()->route('admin.profile.change-password');
                    }
                } else {
                    $request->session()->flash('error', 'Konfirmasi password tidak cocok');
                    return redirect()->route('admin.profile.change-password');
                }
            }
        }

        return view('admin::profile.change_password');
    }
}
