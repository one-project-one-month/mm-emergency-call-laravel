<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyRequest extends Model
{
    protected $guarded = [];

    public function emergency_services(){
      return  $this->belongsTo(EmergencyService::class,'service_id', 'id');
    }

    public function service_providers(){
      return  $this->belongsTo(ServiceProvider::class);
    }
}
