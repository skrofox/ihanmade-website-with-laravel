<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role_id', '2')->latest('id')->paginate(10);
        return view('admin.page.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.page.user.create_user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);
        User::create([
            'name' => 'User',
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->route('user_create')->with('success', ' Create a successful user account');
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->query('keyword', ''));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    // Tìm theo name hoặc email (LIKE)
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");

                    // Nếu từ khoá là số -> match ID chính xác
                    if (ctype_digit($q)) {
                        $sub->orWhere('id', (int) $q);
                    }
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.page.users', compact('users', 'q'));
    }

    /**
     * Display the specified resource.
     */
    public function trash()
    {
        //
        $users = User::onlyTrashed()->paginate(10);
        return view('admin.page.user.trash_user', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function restore($id)
    {
        //
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('user_trash')->with('success', 'Success a restore user');
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
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user_index')->with('success', 'Delete a successful user');
    }
}
