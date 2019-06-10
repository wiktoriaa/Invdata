<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $fillable = ['identifier', 'room_id', 'type_id', 'quality'];

    public function room() {
        return $this->belongsTo('App\Room');
    }

    public function type() {
        return $this->belongsTo('App\Type');
    }

    public function quality() {
    	if ($this->quality == '1')
    		return 'precious';
    	else
    		return '';
    }
}
