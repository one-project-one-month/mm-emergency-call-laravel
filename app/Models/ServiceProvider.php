<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    protected $guarded = [];

    public function emergency_services(){
        return $this->belongsTo(Emergencyservice::class,'service_id', 'id');
     }
}