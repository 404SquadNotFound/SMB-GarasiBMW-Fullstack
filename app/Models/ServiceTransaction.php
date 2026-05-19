<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    protected $guarded = [];

    protected $appends = ['km_masuk'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicles_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'transaction_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'created_by', 'employees_id');
    }

    /**
     * Alias odometer → km_masuk untuk konsistensi frontend
     */
    public function getKmMasukAttribute(): string
    {
        return $this->odometer ? number_format($this->odometer, 0, ',', '.') . ' Km' : '-';
    }
}
