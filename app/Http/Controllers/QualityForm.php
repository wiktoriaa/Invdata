<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Scrap;
use App\QualityLog;
use Auth;

class QualityForm extends Controller
{
    public function confirm(Request $request) {

        if (empty($request['reason'])) {
            return redirect()->route('items.create')->with('error', 'Reason can\'t be empty');
        }

    	$item = Item::where('id', $request['id'])->get();
        $item = $item[0];

    	$id   = $item->identifier;
    	$type = $item->type->name;
    	$room = $item->room->name;

    	$action = $request['item-action'];

    	switch ($action) {
    		case 'scrap':
    			Scrap::create([
                    'identifier' => $item->identifier,
                    'room_id'    => $item->room_id,
                    'type_id'    => $item->type_id
                ]);

    		case 'delete':
    			$item->delete();
    			break;

    		case 'move':
    			$item->update([
                    'room_id' => $request['room'],
                ]);

    			$action .= ' to ' . $request['room_name'];
    			break;

    		default:
    			return redirect()->route('items.create')->with('error', 'Action undefined');
    			exit(0);
    	}

    	QualityLog::create([
    		'identifier' => $id,
    		'type'		 => $type,
    		'room'		 => $room,
    		'action'	 => $action,
    		'reason'	 => $request['reason'],
    		'user'		 => auth::user()->name
    	]);

    	return redirect()->route('items.create')->with('success', 'Action successfull');
    }
}
