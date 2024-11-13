<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    protected $guarded = [];

    public function emergency_request(){
        $this->hasMany(EmergencyRequest::class);
    }
}
