<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Type;
use App\Room;
use App\Scrap;
use App\Log;
use Auth;

class ImportItems extends Controller
{
	private function check_filename($file) {

		if (empty($file)) {
    		return 'Please select file';
    	}

    	return 0;
	}


	private function add_item(array $data) {

		$identifier = $data[0];
		$type 		= $data[1];
		$room 		= $data[2];
		$quality 	= $data[3];

		$room = Room::where('name', $room)->first();
		$type = Type::where('name', $type)->first();

		if ($room == null || $type == null) {
			return;
		}


		if ($quality != '1')
			$quality = '0';

		Item::create([
    		'identifier' => $identifier,
    		'room_id' 	 => $room->id,
    		'type_id' 	 => $type->id,
            'quality'    => $quality
    	]);

    	Log::create([
            'user' => auth::user()->name,
            'room' => $room->name,
            'type' => $type->name,
            'identifier' => $identifier,
            'action'     => 'Add item'
        ]);
	}


	private function move_item(array $data) {

		$identifier = $data[0];
		$type 		= $data[1];
		$room 		= $data[2];
		$new_room 	= $data[3];

		$room = Room::where('name', $room)->first();
		$type = Type::where('name', $type)->first();
		$new_room = Room::where('name', $new_room)->first();

		$item = Item::where('identifier', $identifier)
					->where('type_id', $type->id)
					->where('room_id', $room->id)->first();


		if ($item != null && $new_room != null) {

			$item->room_id = $new_room->id;
			$item->save();

			Log::create([
	            'user' => auth::user()->name,
	            'room' => $room->name,
	            'type' => $type->name,
	            'identifier' => $identifier,
	            'action'     => 'Move item to ' . $new_room->name
	        ]);
		}

	}


    public function create() {
    	return view('import.import');
    }


    public function import(Request $request) {

    	$file = $request['file'];

    	$err = $this->check_filename($file);

    	if ($err) {
    		return redirect()->route('import.import')->with('error', $err);
    					exit(0);
    	}

    	if (($handle = fopen($file, "r")) !== FALSE) {
    		
    		if (isset($request['add-items'])) {
    			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

    				if (count($data) != 4) {
    					return redirect()->route('import.import')->with('error', 'In row must be 4 values');
    					exit(0);
    				}

    				$this->add_item($data);

    			}

    			return redirect()->route('import.import')->with('success', 'Import successfull');
	    	}

	    	else if (isset($request['move-rooms'])) {
	    		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	    			$num = count($data);

	    			if (count($data) != 4) {
    					return redirect()->route('import.import')->with('error', 'In row must be 4 values');
    					exit(0);
    				}

    				$this->move_item($data);

    				return redirect()->route('import.import')->with('success', 'Moving successfull');	    				
	    		}
	    	}
    	}

    	fclose($handle);    	

    	/*$row = 1;
		if (($handle = fopen($file, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        $num = count($data);
		        echo "<p> $num fields in line $row: <br /></p>\n";
		        $row++;
		        for ($c=0; $c < $num; $c++) {
		            echo $data[$c] . "<br />\n";
		        }
		    }
		    fclose($handle);
		}*/

    }

}
