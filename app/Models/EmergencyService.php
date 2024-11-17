<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyService extends Model
{
    protected $guarded = [];

    public function emergency_request(){
       return $this->hasMany(EmergencyRequest::class,'service_id', 'id');
    }


}
