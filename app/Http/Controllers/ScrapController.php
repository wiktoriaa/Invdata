<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Type;
use App\Room;
use App\Scrap;
use App\Log;
use Auth;

class ScrapController extends Controller
{
    private function checkFilters($args) {

        if (!empty($args['room']) && !empty($args['type'])) {
            $items = Scrap::orderBy('id', 'desc')
                        ->where('room_id', $args['room'])
                        ->where('type_id', $args['type']);
        }

        else if (!empty($args['room']) && empty($args['type'])) {
            $items = Scrap::orderBy('id', 'desc')
                        ->where('room_id', $args['room']);
        }

        else if (empty($args['room']) && !empty($args['type'])) {
            $items = Scrap::orderBy('id', 'desc')
                        ->where('type_id', $args['type']);
        }

        else {
            $items = Scrap::orderBy('id', 'desc');
        }

        return $items;
    }


    public function create(Request $request) {

    	$items = $this->checkFilters($request)->paginate(10);
    	$types = Type::all();
    	$rooms = Room::all();

        return view('scrap.create', compact('items', 'types', 'rooms'));
    }


    public function delete(Request $request) {

        $item = Scrap::where('id', $request['id'])->get();
        $item = $item[0];

        $room = Room::where('id', $item->room_id)->get();
        $room = $room[0];

        $type = Type::where('id', $item->type_id)->get();
        $type = $type[0];

        $item->delete();

        Log::create([
            'user' => auth::user()->name,
            'room' => $room->name,
            'type' => $type->name,
            'identifier' => $item->identifier,
            'action'     => 'Deleted from scrap'
        ]);

    	return redirect()->route('scrap.create')->with('success', 'Item deleted successfully');
    }
}
