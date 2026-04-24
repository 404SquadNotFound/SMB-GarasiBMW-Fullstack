<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineType extends Model
{
    use HasFactory;

    protected $primaryKey = 'engine_type_id';
    protected $guarded = [];

    public function carTypes()
    {
        return $this->hasMany(CarType::class, 'engine_type_id', 'engine_type_id');
    }
}
