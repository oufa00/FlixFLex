<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class favoris extends Model
{
    //
    protected $table = 'favoris';


     public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
