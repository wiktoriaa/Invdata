<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Type;
use App\Room;
use App\Scrap;
use App\Log;
use Auth;

class ItemsController extends Controller
{
    private function checkFilters($args) {

        $items = Item::orderBy('id', 'desc');

        if (!empty($args['quality']))
            $items = $items->where('quality', $args['quality']);

        if (!empty($args['room']))
            $items = $items->where('room_id', $args['room']);

        if (!empty($args['type']))
            $items = $items->where('type_id', $args['type']);


        return $items;
    }


    public function create(Request $request) {
    	
        $items = $this->checkFilters($request);

        $rooms = Room::orderBy('name', 'asc')->get();
    	$types = Type::all();

    	if (checkPermission(['superadmin']))
    		$items = $items->paginate(10);
    	else 
    		$items = $items->where('room_id', auth::user()->room->id)->paginate(10);

        return view('items.create', compact('items', 'types', 'rooms'));
    }


    public function store(Request $request) {

    	$request['identifier'] = preg_replace('/\s+/', '', $request['identifier']);

    	if (empty($request['identifier'])) {
    		return redirect()->route('items.create')->with('error', 'Identifier cannot be empty');
    		exit(0);
    	}

    	if (checkPermission(['superadmin']))
    		$room_id = $request['room_id'];
    	else
    		$room_id = auth::user()->room->id;

    	Item::create([
    		'identifier' => $request['identifier'],
    		'room_id' 	 => $room_id,
    		'type_id' 	 => $request['type_id'],
            'quality'    => $request['quality']
    	]);

        //Logging

        $room = Room::where('id', $room_id)->get();
        $room = $room[0];
        $type = Type::where('id', $request['type_id'])->get();
        $type = $type[0];

        Log::create([
            'user' => auth::user()->name,
            'room' => $room->name,
            'type' => $type->name,
            'identifier' => $request['identifier'],
            'action'     => 'Add item'
        ]);

    	return redirect()->route('items.create')->with('success', 'Item added successfully');
    }


    public function delete(Request $request) {

    	$item = Item::where('id' ,$request['id'])->get();
    	$item = $item[0];

    	if (checkPermission(['user'])) {
    		if (auth::user()->room->id != $item->room->id) {
    			return redirect()->route('items.create')->with('error', 'Permission denied');
    			exit(0);
    		}
    	}

        if ($item->quality == '1') {

            $room = $request['room'];
            $room_name = Room::where('id', $request['room'])->get();
            $room_name = $room_name[0]->name;

            if (isset($request['scrap']))
                $action = 'scrap';
            else if (isset($request['delete']))
                $action = 'delete' ;
            else if (isset($request['move']))
                $action = 'move';
            else
                return redirect()->route('items.create')->with('error', 'Error: action not found');

            return view('items.quality-form', compact('item', 'action', 'room', 'room_name'));
            exit(0);
        }

        else {

            $room = Room::where('id', $item->room_id)->get();
            $room = $room[0];
            $type = Type::where('id', $item->type_id)->get();
            $type = $type[0];

            if (isset($request['scrap'])) {
                Scrap::create([
                    'identifier' => $item->identifier,
                    'room_id'    => $item->room_id,
                    'type_id'    => $item->type_id
                ]);
                $item->delete();

                $action = 'Transfer to scrap';
            }

            else if (isset($request['delete'])) {

                $item->delete();
                $action = 'Item deleted';

            }

            else if (isset($request['move'])) {
                $item->update([
                    'room_id' => $request['room'],
                ]);

                $new_room = Room::where('id', $request['room'])->get();
                $new_room = $new_room[0];

                $action = 'Item moved to '. $new_room->name;
            }

            else {
                return redirect()->route('items.create')->with('error', 'Error: action not found');
            }

            Log::create([
                'user' => auth::user()->name,
                'room' => $room->name,
                'type' => $type->name,
                'identifier' => $item->identifier,
                'action'     => $action
            ]);


            return redirect()->route('items.create')->with('success', $action . ' successfully');
        }

    	
    }

}
