<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartStock extends Model
{
    use HasFactory;

    protected $primaryKey = 'stock_id';
    protected $guarded = [];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'sparepart_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
