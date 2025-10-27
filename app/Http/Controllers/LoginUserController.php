<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login_page()
    {
        return view('home-user.login', [
            'title' => 'Login - Hyperlink'
        ]);
    }

    public function login_proses(Request $request)
    {
        // dd($request->all());
        // validasi input
        $request->validate([
            'no_hp'     => 'required',
            'password'  => 'required',
        ]);

        // cari user berdasarkan no_hp
        $user = User::where('no_hp', $request->no_hp)->first();

        // cek user & password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Nomor HP atau password salah');
        }

        // loginin user
        Auth::login($user);

        // langsung generate remember_token baru
        $user->setRememberToken(Str::random(60));
        $user->save();

        // redirect sesuai role
        switch ($user->role) {
            case 'administrasi':
                return redirect()->route('login_page')->with('success', 'Login berhasil sebagai Admin!');
            case 'pemilik':
                return redirect()->route('login_page')->with('success', 'Login berhasil sebagai Pemilik!');
            default:
                return redirect()->route('home-user')->with('success', 'Login berhasil sebagai Customer!');
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->setRememberToken(null);
            $user->save();
        }
        Auth::logout();

        return redirect()->route('login_page')->with('success', 'Logout berhasil!');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
