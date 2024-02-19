<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('role')->orderBy('name', 'ASC');

        $word = null;
        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $users = $users->where('name', 'LIKE', '%'.$word.'%')
                ->orWhere('email', 'LIKE', '%'.$word.'%');
        }

        $users = $users->paginate(20);
        if (!empty($word)) {
            $users = $users->appends(['search'=>$word]);
        }

        return Inertia::render('User/Index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/UserForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8']
        ])->validate();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->save();

        return route('user.index');
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
        $auth = Auth::user();
        $roles = json_decode($auth->role->access_items);
        if ($auth->id != $id) {
            if (!property_exists($roles, 'user') || !in_array('edit', $roles->user)) {
                return back();
            }
        }
        $user = User::find($id);
        return Inertia::render('User/UserForm', ['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auth = Auth::user();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return back();
    }

    /**
     * Change or update password form
     */
    public function editPass()
    {
        return Inertia::render('User/PasswordForm');
    }

    /**
     * Check old password data
     */
    public function updatePass(Request $request)
    {
        Validator::make($request->all(), [
            'old_password' => ['required', 'string', new MatchOldPassword],
            'password' => ['required', 'string', 'confirmed', 'min:8']
        ])->validate();

        $auth = Auth::user();
        $user = User::find($auth->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return back();
    }

    public function getRoles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function getRoleInfo(string $id)
    {
        $role = Role::find($id);

        return response()->json($role);
    }
}
