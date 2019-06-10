<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QualityLog extends Model
{
    protected $table = 'quality_log';
    protected $fillable = ['identifier', 'type', 'room', 'action', 'reason', 'user'];
}
