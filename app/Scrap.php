<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Scrap extends Model
{
	protected $fillable = ['identifier', 'room_id', 'type_id'];

	protected $table = 'scrap';

    public function room() {
        return $this->belongsTo('App\Room');
    }

    public function type() {
        return $this->belongsTo('App\Type');
    }
}
