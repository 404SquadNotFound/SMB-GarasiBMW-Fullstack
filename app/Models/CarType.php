<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use HasFactory;

    protected $primaryKey = 'car_type_id';
    protected $guarded = [];

    public function engineType()
    {
        return $this->belongsTo(EngineType::class, 'engine_type_id', 'engine_type_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'car_type_id', 'car_type_id');
    }
}
