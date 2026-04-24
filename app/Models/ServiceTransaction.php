<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicles_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'transaction_id');
    }
}
