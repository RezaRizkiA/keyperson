<?php
namespace App\Http\Controllers;

use App\Models\ExpertiseCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_callback(Request $request)
    {
        if (session()->has('url.intended')) {
            $redirectTo = session()->get('url.intended');
        } else {
            $redirectTo = false;
        } //if intended url is available

        $google_user   = Socialite::driver('google')->user();
        $avatarUrl     = $google_user->getAvatar();
        $imageContents = file_get_contents($avatarUrl);
        $filename      = 'avatars/' . uniqid() . '.png';

        if (User::where('email', $google_user->email)->exists()) {
            $user = User::where('email', $google_user->email)->first();
            if ($user->google_id == null) {
                $user->update([
                    'google_id' => $google_user->id,
                ]);
                Auth::login($user, true);
            } elseif ($user->google_id == $google_user->id) {
                Auth::login($user, true);
            }
        } else {
            $user = User::create([
                'name'              => $google_user->name,
                'roles'             => ['user'],
                'email'             => $google_user->email,
                'google_id'         => $google_user->id,
                'email_verified_at' => now(),
            ]);
            Auth::login($user, true);
        }

        $user->slug ??= slugName($user->name, $user->id);
        if (! $user->picture) {
            Storage::disk('s3')->put($filename, $imageContents, 'public');
            $user->picture = $filename;
        }
        $user->save();

        $credentials = [
            'user'      => Auth::user()->id,
            'token_jwt' => Auth::user()->id,
        ];
        $request->session()->regenerate();
        $request->session()->put('loggedUser', $credentials);

        if ($redirectTo != false) {
            session()->forget('url.intended');
            return redirect($redirectTo);
        }
        return redirect()->route('profile');
    }

    public function register()
    {
        $user                 = Auth::user();
        $expertise_categories = ExpertiseCategory::with('expertises')->get();

        $client = $user->client;
        $expert = $user->expert;
        return view('register.register_action', compact('expertise_categories', 'client', 'expert'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function renew_password(Request $request)
    {
        $user = Auth::user();

        // 1. Menentukan aturan validasi secara kondisional
        $rules = [
            'new_password' => 'required|min:4|confirmed',
            // 'confirmed' akan memeriksa field new_password_confirmation
        ];

        if ($user->password !== null) {
            // Jika user sudah punya password lama, current_password wajib dan harus cocok
            $rules['current_password'] = 'required|current_password';
        } else {
            // Jika kolom password kosong (user belum pernah set password),
            // maka current_password cukup nullable (boleh kosong)
            $rules['current_password'] = 'nullable|current_password';
        }

        // 2. Jalankan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 3. Setelah validasi:
        // Jika user sudah punya password lama, pastikan current_password benar (sebenarnya rule current_password sudah mengecek ini).
        // Namun, untuk keamanan ganda, kita bisa periksa ulang:
        if ($user->password !== null) {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->withInput();
            }
        }

        // 4. Simpan password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Password has been updated successfully!');
    }

    public function renew_picture(Request $request)
    {
        // Validasi gambar yang di-upload
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:800', // Validasi format dan ukuran
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Hapus file gambar lama jika ada
        if ($user->picture) {
            // Menghapus file gambar lama di S3
            Storage::disk('s3')->delete($user->picture);
        }

        // Menyimpan gambar baru ke S3
        if ($request->hasFIle('picture')) {
            $image    = $request->file('picture');
            $filename = 'avatars/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
        }

        // Simpan path gambar baru ke database
        $user->picture = $filename;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function renew_profile(Request $request)
    {
        $user = Auth::user();
        // Validasi input, termasuk validasi slug yang unik
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'gender'    => 'required|in:L,P', // L = Male, P = Female
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'slug_name' => 'nullable|unique:users,slug,' . $user->id . '|alpha_dash', // Slug unik kecuali slug milik user yang sedang diupdate
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Periksa apakah ada perubahan di input yang dimasukkan, jika tidak, biarkan kosong
        $user->name    = $request->name ?: $user->name;
        $user->gender  = $request->gender ?: $user->gender;
        $user->phone   = $request->phone ?: $user->phone;
        $user->address = $request->address ?: $user->address;

        // Perbarui slug hanya jika ada perubahan
        if ($request->slug_name && $request->slug_name != $user->slug) {
            $user->slug = $request->slug_name;
        }

        // Simpan perubahan di database
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function profile()
    {
        return view('profile');
    }

    public function register_client_post(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        $fileUpload = [
            'file_author_photo'      => [
                'db_column'    => 'author_photo',
                'storage_path' => 'author/profiles/',
            ],
            'file_banner_background' => [
                'db_column'    => 'banner_background',
                'storage_path' => 'author/background/',
            ],
            'file_logo'              => [
                'db_column'    => 'logo',
                'storage_path' => 'author/logo/',
            ],
        ];

        if ($user->client == null) {
            $client = $user->client()->create($request->all()); //data baru
        } else {
            $client = $user->client;          // data lama
            $client->update($request->all()); // data di perbarui
        }

        foreach ($fileUpload as $inputName => $fileInfo) {
            if ($request->hasFile($inputName)) {
                $file              = $request->file($inputName);
                $dbColumn          = $fileInfo['db_column'];
                $storagePathFolder = $fileInfo['storage_path'];

                $filename = $storagePathFolder . uniqid() . '.' . $file->getClientOriginalExtension(); // data baru

                if (! empty($user->client->dbColumn)) { //kossong
                    Storage::disk('s3')->delete($user->client->dbColumn);
                }
                Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
                $client->$dbColumn = $filename;
            }
        }
        $client->save();
        return redirect()->route('profile');
    }

    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('profile');
        }
        return redirect()->back()->withError([
            'email' => 'Email atau Password Tidak Valid!!',
        ])->withInput();
    }

    public function register_expert_post(Request $request)
    {
        $needProcesses = $request->only(['licenses', 'gallerys']);
        $directProcess = $request->except(['licenses', 'gallerys', '_token']);

        $user = Auth::user();
        if ($user->expert == null) {
            $expert = $user->expert()->create($directProcess); //data baru
        } else {
            $expert = $user->expert;         // data lama
            $expert->update($directProcess); // data di perbarui
        }

        foreach ($needProcesses as $key_process => $need_process) { // key ini untuk path 'licenses', 'gallery'
            $the_process = is_array($expert->$key_process ?? null) ? $expert->$key_process : [];
            foreach ($need_process as $key_data_db => $process_data) {            // key ini untuk database data keberapa '[0]', '[1]'...
                foreach ($process_data as $key_item => $value) {                      // key ini untuk index data keberapa 'certification', 'attachment' or...
                                                                                          // cek apakah data yang baru ini string atau file
                    if ($request->hasFile("{$key_process}.{$key_data_db}.{$key_item}")) { // jika file proses ke s3 baru name file simpan database
                                                                                              // cek apakah file yang sebelumnya ada
                        if (isset($the_process[$key_data_db][$key_item])) {                   // hapus dulu di s3 nya
                            Storage::disk('s3')->delete($the_process[$key_data_db][$key_item]);
                        }

                        $file     = $value;
                        $filename = "expert/{$key_process}/" . uniqid() . '.' . $file->getClientOriginalExtension(); // data baru
                        Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
                        $the_process[$key_data_db][$key_item] = $filename;
                    } else { // langsung update
                        $the_process[$key_data_db][$key_item] = $value;
                    }
                }
            }
            $expert->$key_process = $the_process;
            $expert->save();
        }

        return redirect()->route('profile');
    }
}
