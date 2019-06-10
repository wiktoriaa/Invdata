<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Auth;

class UserController extends Controller
{
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    protected function create(array $data) {

        if ($data['is_super'] == '0')
            $room = $data['room_id'];
        else
            $room = null;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'room_id' => $room,
            'is_super' => $data['is_super'],
        ]);
    }


    public function display() {
    	$users = User::orderBy('is_super', 'desc')->paginate(10);
    	$rooms = Room::orderBy('id', 'desc')->get();

    	return view('users.create', compact('users', 'rooms'));
    }


    public function store(Request $request) {

    	$this->validator($request->all())->validate();

        if (empty($request['room_id']) && $request['is_super'] == '0') {
            return redirect()->route('users.create')->with('error', 'Only superadmin can access all rooms');
        }

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        return redirect()->route('users.create')->with('success', 'User added successfully');
    }


    public function update(Request $request) {

        if (empty($request['room_id']) && $request['is_super'] == '0') {
            return redirect()->route('users.create')->with('error', 'Only superadmin can access all rooms');
        }

    	if ($request['id'] == auth::user()->id)
    		$super = 1;
    	else
    		$super = $request['is_super'];

        if ($request['is_super'] == '0')
            $room = $request['room_id'];
        else
            $room = null;

    	User::where('id', $request['id'])
    			->update([
    				'name'     => $request['name'],
    				'email'    => $request['email'],
    				'room_id'  => $room,
    				'is_super' => $super,
    			]);

    	return redirect()->route('users.create')->with('success', 'User updated successfully');
    }


    public function delete(Request $request) {
    	if ($request['id'] == auth::user()->id) {
    		return redirect()->route('users.create')->with('error', 'You cannot delete yourself');
    		exit(0);
    	}
        else {
            User::where('id', $request['id'])->delete();
            return redirect()->route('users.create')->with('success', 'User deleted successfully');
        }
    }

}
