<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Scrap;
use App\Item;
use App\User;

class RoomsController extends Controller
{
    public function create() {
    	$rooms = Room::paginate(10);
        return view('rooms.create', compact('rooms'));
    }


    public function store(Request $request) {

        if (empty($request['rooms'])) {
            return redirect()->route('rooms.create')->with('error', 'Rooms field cannot be empty');
            exit(0);
        }

    	$rooms = preg_replace('/\s+/', '', $request['rooms']);
    	$rooms = explode(',', $rooms);


    	foreach ($rooms as $room => $val) {
            
            if (!empty($val)) {
                    $check = Room::where('name', $val)->first();

                    if ($check == null) {
                        Room::create([
                            'name' => $val,
                        ]);
                    }
            }
        }

    	return redirect()->route('rooms.create')->with('success', 'Room(s) added successfully');
    }


    public function delete(Request $request) {

        $id = $request['id'];
        $in_scrap = Scrap::where('room_id', $id)->get()->count();
        $in_items = Item::where('room_id', $id)->get()->count();
        $in_users = User::where('room_id', $id)->get()->count();

        if ($in_scrap > 0 || $in_items > 0 || $in_users > 0) {
            return redirect()->route('rooms.create')->with('error', 'You cannot delete this room because it\'s in use');
            exit(0); 
        }

        Room::where('id', $id)->delete();

        return redirect()->route('rooms.create')->with('success', 'Room deleted successfully');  
    }

}
