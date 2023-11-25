<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\guru;
use App\Models\Suara;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'updateVoteStatus', 'users', 'user', 'guruTerasik', 'getGuru', 'category', 'postSuara', 'vote']]);
    }

    public function users()
    {
        $users = Auth::user();

        return response()->json(['users' => $users]);
    }

    public function user()
    {
        $user = User::all();

        return response()->json(['user' => $user]);
    }

    public function postSuara(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|in:terasik,terkiller,terinspiratif',
            // Tambahan validasi jika diperlukan
        ]);

        // Dapatkan data User
        $user = Auth::user();

        // Simpan suara ke dalam tabel suara
        $guru = Guru::find($request->guru_id);
        if ($guru) {
            $suara = $guru->suara()->create([
                'user_id' => $user->id,
                'kategori' => $request->kategori,
            ]);

            // Update total suara guru berdasarkan kategori suara
            $guru->increment($request->kategori);
        } else {
            return response()->json([
                'message' => 'Guru not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Suara berhasil disimpan',
        ]);
    }

    public function category()
    {
        $category = Category::all();

        return response()->json(['category' => $category]);
    }

    // AuthController.php

    public function updateVoteStatus(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer',
            'hasVoted' => 'required|boolean',
            'hasVotedTerkiller' => 'required|boolean',
            'hasVotedTerinspiratif' => 'required|boolean',
        ]);

        $user = User::find($request->userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update([
            'hasVoted' => $request->hasVoted,
            'hasVotedTerkiller' => $request->hasVotedTerkiller,
            'hasVotedTerinspiratif' => $request->hasVotedTerinspiratif,
        ]);

        return response()->json(['message' => 'Vote status updated successfully']);
    }

    public function vote(Request $request)
    {
        $guru = Guru::find($request->guruId);

        if (!$guru) {
            return response()->json(['error' => 'Guru not found'], 404);
        }

        // Update nilai suara berdasarkan kategori terpilih
        $kategoriTerpilih = $request->category;
        $guru->{$kategoriTerpilih}++;

        // Simpan perubahan
        $guru->save();

        // Update hasVoted pada model User
        $user = Auth::user();

        // Pastikan atribut 'hasVoted' ada di model User
        if (isset($user->hasVoted)) {
            $user->hasVoted = true; // Update hasVoted attribute

            // Simpan perubahan pada user
            $user->save();

            return response()->json(['message' => 'Vote recorded successfully']);
        } else {
            return response()->json(['error' => 'Attribute "hasVoted" not found in User model']);
        }
    }

    public function getGuru()
    {
        $guru = Guru::all();

        return response()->json(['guru' => $guru]);
    }

    public function guruTerasik(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'terasik' => 'required|integer',
        ]);

        $guruTerasik = Guru::create([
            'nama_guru' => $request->nama_guru,
            'terasik' => $request->terasik,
        ]);

        return response()->json(['guruTerasik' => $guruTerasik]);
    }
    public function guruTerkiller(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'terasik' => 'required|string|max:255',
        ]);

        $guru = Guru::create([
            'nama_guru' => $request->nama_guru,
            'nama_guru' => $request->nama_guru,
        ]);

        return response()->json(['guru' => $guru]);
    }
    public function guruTerinspiratif(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'terasik' => 'required|string|max:255',
        ]);

        $guru = Guru::create([
            'nama_guru' => $request->nama_guru,
            'nama_guru' => $request->nama_guru,
        ]);

        return response()->json(['guru' => $guru]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'role' => $user->role,
            'hasVoted' => $user->hasVoted,
            'hasVotedTerkiller' => $user->hasVotedTerkiller,
            'hasVotedTerinspiratif' => $user->hasVotedTerinpiratif,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }



    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
