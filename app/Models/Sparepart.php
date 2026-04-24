<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $primaryKey = 'sparepart_id';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id', 'car_type_id');
    }
}
