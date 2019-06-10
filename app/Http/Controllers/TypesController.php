<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Scrap;
use App\Item;
use App\User;

class TypesController extends Controller
{
    public function create() {
    	$types = Type::paginate(10);
        return view('types.create', compact('types'));
    }

    public function store(Request $request) {

        if (empty($request['types'])) {
            return redirect()->route('types.create')->with('error', 'Types field cannot be empty');
            exit(0);
        }

    	$types = preg_replace('/\s+/', '', $request['types']);
    	$types = explode(',', $types);

    	foreach ($types as $type => $val) {
            if (!empty($val)) {
                    $check = Type::where('name', $val)->first();

                    if ($check == null) {
                        Type::create([
                            'name' => $val,
                        ]);
                    }
            }
    	}

    	return redirect()->route('types.create')->with('success', 'Type(s) added successfully');
    }

    public function delete(Request $request) {
        
        $id = $request['id'];
        $in_scrap = Scrap::where('type_id', $id)->get()->count();
        $in_items = Item::where('type_id', $id)->get()->count();

        if ($in_scrap > 0 || $in_items > 0) {
            return redirect()->route('types.create')->with('error', 'You cannot delete this type because it\'s in use');
            exit(0); 
        }

        Type::where('id', $id)->delete();

        return redirect()->route('types.create')->with('success', 'Type deleted successfully');  
    }

}
