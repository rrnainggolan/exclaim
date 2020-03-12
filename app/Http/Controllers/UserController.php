<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    /**
     * Controller constructors.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userService = new UserService();
        $users = $userService->getUsers();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        $userService = new UserService();
        $user = $userService->createUser($userData);

        return redirect()->route('users.index')
            ->with('flash_message', 'User added')
            ->with('class', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        $userService = new UserService();
        $user = $userService->updateUser($user, $userData);

        return redirect()->route('users.index')
            ->with('flash_message', 'User updated')
            ->with('class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $userService = new UserService();
        $userService->deleteUser($user);

        return response()->json([
            'name' => $userName,
            'success' => true
        ]);
    }

    /**
     * Show the form for editing password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id)
    {
        return view('users.edit_password');
    }

    /**
     * Update the password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        $this->validate($request, [
            'current_password'=>'required|min:6',
            'password'=>'required|min:6|confirmed'
        ]);

        if(Hash::check($request->current_password, Auth::user()->password)) {
            $request->user()->fill([
                'password' => Hash::make($request->password)
            ])->save();

            return redirect()->route('home')
                ->with('flash_message', 'Your password successfully updated.')
                ->with('class', 'success');
        } else {
            $errors = new MessageBag();
            $errors->add('current_password_not_match', 'Current password not match! Please try again.');

            return redirect()->route('edit.password', ['id' => $id])
                ->with('errors', $errors)
                ->with('class', 'alert');
        }
    }
}
