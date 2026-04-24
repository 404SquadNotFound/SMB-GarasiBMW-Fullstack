<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $primaryKey = 'vehicles_id';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id', 'car_type_id');
    }

    public function transactions()
    {
        return $this->hasMany(ServiceTransaction::class, 'vehicle_id', 'vehicles_id');
    }
}
